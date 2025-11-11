<?php

declare(strict_types=1);

namespace Server\Game\Battle;

use Psr\Log\LogLevel;
use React\EventLoop\Loop;
use React\EventLoop\TimerInterface;
use React\Promise\Deferred;
use React\Promise\Promise;
use Server\Core\Exception\CommonException;
use Server\Events\ClientEvent;
use Server\Game\Managers\UserManager;
use Server\Game\Models\User;
use Server\Game\Utility\BattleUtility;
use Server\Game\Utility\UserUtility;
use Server\Game\Consts;

/**
 *
 */
abstract class BaseBattle
{
    const BATTLE_LIFETIME_AFTER_FINISH = 30;

    /**
     * @var int
     */
    protected int $id;

    /**
     * @var bool
     */
    protected bool $isStarted = false;

    /**
     * @var bool
     */
    protected bool $isFinished = false;

    /**
     * @var float
     */
    protected float $lastClientUnitDataUpdate = 0;

    /**
     * @var \React\EventLoop\TimerInterface|null
     */
    protected ?TimerInterface $timer;

    /**
     * @var \Server\Game\Battle\BattleTeam
     */
    protected BattleTeam $team1;

    /**
     * @var \Server\Game\Battle\BattleTeam
     */
    protected BattleTeam $team2;

    /**
     * @var array
     */
    protected array $users = [];

    /**
     * @var Deferred|null
     */
    protected ?Deferred $battleEndDeferred;

    /**
     * Class constructor
     */
    public function __construct(int $id)
    {
        $this->id = $id;
        $this->battleEndDeferred = new Deferred();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param  \Server\Game\Battle\BattleTeam  $team
     *
     * @return void
     */
    protected function setTeam1(BattleTeam $team): void
    {
        $this->team1 = $team;
    }

    /**
     * @return \Server\Game\Battle\BattleTeam
     */
    public function getTeam1(): BattleTeam
    {
        return $this->team1;
    }

    /**
     * @param  \Server\Game\Models\User  $user
     *
     * @return void
     */
    public function addUser(User $user): void
    {
        $this->users[$user->getId()] = $user;
    }

    /**
     * @param  \Server\Game\Battle\BattleTeam  $team
     *
     * @return void
     */
    protected function setTeam2(BattleTeam $team): void
    {
        $this->team2 = $team;
    }

    /**
     * @return \Server\Game\Battle\BattleTeam
     */
    public function getTeam2(): BattleTeam
    {
        return $this->team2;
    }

    public function start()
    {
        if ($this->isStarted) {
            throw new \Exception('Battle has been already started!');
        }

        if ($this->isFinished) {
            throw new \Exception('Battle has been already finished!');
        }

        $this->isStarted = true;

        $this->processBattle(true);

        $this->timer = Loop::addPeriodicTimer(0.1, function () {
            $this->processBattle();
        });
    }

    protected function processBattle($initial = false)
    {
        if ($this->getTeam1()->getTeamHp() <= 0 || $this->getTeam2()->getTeamHp() <= 0) {
            $this->isFinished = true;
            Loop::cancelTimer($this->timer);
            $this->updateClientUnitData();
            $this->generateResults();

            return;
        }


        $buffTicksResults = [];
        foreach ([$this->getTeam1(), $this->getTeam2()] as $team) {
            /** @var \Server\Game\Battle\BattleUnit $unit */
            foreach ($team->getUnits() as $unitId => $unit) {
                if (!$unit->isAlive()) {
                    continue;
                }

                // initial effects
                $initialEffects = $unit->getEffects();

                // initial CDs
                if ($initial) {
                    foreach ($unit->getSkills() as $skill) {
                        $cooldownTime = BattleUtility::calculateSkillFinalCooldown($unit, $skill);
                        $skill->setIsOnCooldownUntil(microtime(true) + $cooldownTime);
                    }
                }

                // initial buffs from talents
                if ($initial) {
                    $this->processTalents($unit);
                }

                // AI
                if ($unit->isEnableAI()) {
                    $this->doSomeAiAction($unit, $team, $team == $this->getTeam1() ? $this->getTeam2() : $this->getTeam1());
                }

                // Buff ticks processing
                foreach ($unit->getBuffs() as $buffCode => $buff) {
                    $this->processBuffOnUnit($unit, $buff, $buffTicksResults);
                }

                // recalculate effects
                $unit->setEffects([]);
                foreach ($unit->getBuffs() as $buffCode => $buff) {
                    $effects = $buff->getEffects();

                    foreach ($effects as $effect) {
                        $unit->addEffect($effect);
                    }
                }

                foreach ($unit->getEffects() as $effect) {
                    if ($effect->getType() === Consts\Effect::EFFECT_TYPE_STUN) {
                        // stun effect has not existed yet
                        if (!isset($initialEffects[Consts\Effect::EFFECT_TYPE_STUN])) {
                            $buffTicksResults['units'][$unit->getId()]['effects'][Consts\Effect::EFFECT_TYPE_STUN] = true;
                            $this->interruptUnitAction($unit);
                        }
                    }
                }

                if (!empty($buffTicksResults['units'][$unit->getId()]['buffTicks'])
                || !empty($buffTicksResults['units'][$unit->getId()]['effects']) ) {
                    $buffTicksResults['units'][$unit->getId()]['data'] = BattleUtility::prepareBattleUnitDataForResponse($unit);
                }
            }
        }

        if (!empty($buffTicksResults)) {
            foreach ($this->users as $user) {
                $client = $user->getClient();
                $client->triggerEvent(
                    ClientEvent::BATTLE_UNIT_BUFF_TICK,
                    $buffTicksResults
                );
            }
        }
        unset($buffTicksResults);

        // Update client UI data
        if ($initial || microtime(true) - $this->lastClientUnitDataUpdate > 1) {
            $this->updateClientUnitData();
            $this->lastClientUnitDataUpdate = microtime(true);
        }
    }

    /**
     * @param BattleUnit $unit
     * @param BattleBuff $buff
     * @param array $buffTicksResults
     * @return void
     */
    protected function processBuffOnUnit(BattleUnit $unit, BattleBuff $buff, array &$buffTicksResults)
    {
        if ($buff->getCurrentTick() < $buff->getTicks()) {
            if ($buff->getNextTick() < microtime(true)) {
                $buff->setCurrentTick($buff->getCurrentTick() + 1);
                $buff->setNextTick(microtime(true) + $buff->getTickTime());

                // IF case only to have a possibility to collapse code blocks, no logic sense
                // calculate damage sources
                // @todo rework
                if (true) {
                    $damageSources = $buff->getDamageSources();

                    foreach ($damageSources as $damageSource) {
                        $damageFinal = BattleUtility::calculateBuffTickDamage($unit, $damageSource, $buff->getTicks());
                        if ($damageFinal > 0) {
                            $unit->changeHp(-$damageFinal);
                            $buffTicksResults['units'][$unit->getId()]['buffTicks'][$buff->getCode()]['damage'][$damageSource->getType()][] = $damageFinal;
                        }
                    }
                }

                // apply multipliers
                if (true) {
                    $statMultipliers = $buff->getMultipliers();

                    // @todo implement multiple sources of the same buff support
                    foreach ($statMultipliers as $statMultiplier) {
                        $valueFinal = BattleUtility::calculateBuffMultiplierValue($unit, $statMultiplier);
                        $unit->addStatMultiplierByTypeAndCode($statMultiplier->getType(), $buff->getCode(), $valueFinal);
                    }
                }
            }
        } elseif ($buff->getEndTime() < microtime(true)) {
            $unit->removeBuff($buff->getCode());

            // remove multipliers
            if (true) {
                $statMultipliers = $buff->getMultipliers();

                foreach ($statMultipliers as $statMultiplier) {
                    $unit->removeStatMultiplierByTypeAndCode($statMultiplier->getType(), $buff->getCode());
                }
            }
        }
    }

    /**
     * @param BattleUnit $initiator
     * @param BattleSkill $skill
     * @param BattleUnit $skillTarget
     * @param BattleUnit|null $friendTarget
     * @param BattleUnit|null $enemyTarget
     *
     * @return void
     */
    protected function processTalents(
        BattleUnit $unit,
    ) {
        try {
            $battleTalents = $unit->getTalents();

            foreach ($battleTalents as $battleTalent) {
                $talentBuffs = $battleTalent->getBuffSources();

                /** @var \Server\Game\Battle\BattleBuffSource $buffSource */
                foreach ($talentBuffs as $buffCode => $buffSource) {
                    $buff = BattleBuffFactory::createBattleBuffFromBattleBuffSource($buffSource, $unit);

                    if ($buffSource->getTargetType() !== Consts\Target::TARGET_TYPE_SELF) {
                        throw new \Exception('Talent buffs must have target SELF: ' . $buffSource->getCode());
                    }

                    $unit->addBuff($buff->getCode(), $buff);
                }
            }
        } catch (\Throwable $error) {
            $error = new CommonException($error->getMessage(), $error->getCode(), $error);
            App()->getLogger()->logException($error);
        }
    }

    /**
     * @return void
     */
    protected function updateClientUnitData()
    {
        $unitData = [];

        foreach ([$this->getTeam1(), $this->getTeam2()] as $team) {
            foreach ($team->getUnits() as $unitId => $unit) {
                $unitData[$unitId] = BattleUtility::prepareBattleUnitDataForResponse($unit);
            }
        }

        foreach ($this->users as $user) {
            $client = $user->getClient();
            $client->triggerEvent(
                ClientEvent::BATTLE_UPDATE_UNIT_DATA,
                $unitData
            );
        }
    }

    /**
     * @param BattleUnit $unit
     * @param BattleTeam $friendTeam
     * @param BattleTeam $enemyTeam
     *
     * @return void
     *
     * @throws \Exception
     */
    protected function doSomeAiAction(BattleUnit $unit, BattleTeam $friendTeam, BattleTeam $enemyTeam)
    {
        if ($unit->isLocked()) {
            return;
        }

        foreach ($unit->getSkills() as $skill) {
            if ($skill->isOnCooldown()) {
                continue;
            }

            $targetType = $skill->getTargetType();

            // define enemy target for the AI action
            $enemyTargetHp = PHP_INT_MAX;
            $enemyTarget = null;
            // @todo implement more accurate enemy selection logic
            /** @var \Server\Game\Battle\BattleUnit $enemy */
            foreach ($enemyTeam->getUnits() as $enemy) {
                if ($enemy->getHp() < $enemyTargetHp) {
                    $enemyTargetHp = $enemy->getHp();
                    $enemyTarget = $enemy;
                }
            }

            // define friend target for the AI action
            $friendTargetHp = PHP_INT_MAX;
            $friendTarget = null;

            // @todo implement more accurate friend selection logic
            /** @var \Server\Game\Battle\BattleUnit $friend */
            foreach ($friendTeam->getUnits() as $friend) {
                if ($friend->getHp() < $friendTargetHp) {
                    $friendTargetHp = $friend->getHp();
                    $friendTarget = $friend;
                }
            }

            $this->unitUseSkill($unit->getId(), $skill->getCode(), $enemyTarget->getId(), $friendTarget->getId());
        }
    }

    /**
     * @param int $initiatorId
     * @param string|null $skillCode
     * @param int|null $enemyTargetId
     * @param int|null $friendTargetId
     *
     * @return void
     *
     * @throws \Exception
     */
    public function unitUseSkill(int $initiatorId, ?string $skillCode, ?int $enemyTargetId, ?int $friendTargetId): void
    {
        /** @var BattleUnit $initiator */
        $initiator = null;
        /** @var BattleUnit $enemyTarget */
        $enemyTarget = null;
        /** @var BattleUnit $friendTarget */
        $friendTarget = null;
        /** @var BattleUnit $skillTarget */
        $skillTarget = null;

        foreach ([$this->getTeam1(), $this->getTeam2()] as $team) {
            foreach ($team->getUnits() as $unitId => $unit) {
                if ($initiatorId === $unitId) {
                    $initiator = $unit;
                }
                if ($enemyTargetId && $enemyTargetId === $unitId) {
                    $enemyTarget = $unit;
                }
                if ($friendTargetId && $friendTargetId === $unitId) {
                    $friendTarget = $unit;
                }
            }
        }

        if (null === $initiator) {
            throw new \Exception('Wrong initiator ID!');
        }

        /*if (null === $target) {
            throw new \Exception('Wrong target ID!');
        }*/

        $initiatorSkill = $initiator->getSkillByCode($skillCode);

        if (!$initiatorSkill) {
            throw new \Exception('Wrong skill code!');
        }

        if ($initiatorSkill->getTargetType() === Consts\Target::TARGET_TYPE_ENEMY) {
            if ($enemyTarget->getTeam() === $initiator->getTeam()) {
                throw new \Exception('Only enemy target is allowed for this skill!');
            }
            $skillTarget = $enemyTarget;
        }

        if ($initiatorSkill->getTargetType() === Consts\Target::TARGET_TYPE_FRIEND) {
            if ($friendTarget->getTeam() !== $initiator->getTeam()) {
                throw new \Exception('Only friend target is allowed for this skill!');
            }
            $skillTarget = $friendTarget;
        }

        if ($initiatorSkill->getTargetType() === Consts\Target::TARGET_TYPE_SELF) {
            $skillTarget = $initiator;
        }

        if ($initiator->isLocked() || $initiatorSkill->isOnCooldown()) {
            // do nothing
            return;
        }

        // calculate manacost
        $manacost = $initiatorSkill->getMpCost();

        if ($manacost > 0) {
            if ($initiator->getMp() < $manacost) {
                return;
            }

            $initiator->changeMp(-$manacost);
        }

        // calculate cooldown
        $cooldownTime = BattleUtility::calculateSkillFinalCooldown($initiator, $initiatorSkill);
        $initiatorSkill->setIsOnCooldownUntil(microtime(true) + $cooldownTime);

        $castingTime = $initiatorSkill->getCastingTime();
        $hitTime = $initiatorSkill->getHitTime();
        $hitCastDiff = $hitTime - $castingTime;
        if ($hitCastDiff < 0) {
            $hitCastDiff = 0;
        }
        $animationTime = 0.1;
        foreach ($initiatorSkill->getAnimationChain() as $animation) {
            $animationTime += $animation['duration'];
        }

        // casting timer that ends when user ends casting (after that skill can not be interrupted)
        $castingTimer = Loop::addTimer(
            $castingTime,
            function () use ($initiator, $initiatorSkill, $skillTarget, $friendTarget, $enemyTarget, $hitCastDiff) {
                $initiator->setCastingTimer(null);
                $specialEffects = $initiatorSkill->getSpecialEffects();
                $returnData = [];

                foreach ($specialEffects as $specialEffect) {
                    $key = $specialEffect['key'] ?? null;
                    $start = (string)($specialEffect['start'] ?? Consts\SpecialEffect::EFFECT_START_ON_CASTEND);
                    $zone = (string)($specialEffect['zone'] ?? Consts\SpecialEffect::EFFECT_ZONE_SELF);
                    $duration = (float)($specialEffect['duration'] ?? 0);
                    $skeletonPointKey = (string)($specialEffect['skeletonPointKey'] ?? null);

                    if ($key && $start === Consts\SpecialEffect::EFFECT_START_ON_CASTEND) {
                        $returnData[] = [
                            'key' => $key,
                            'duration' => $duration,
                            'zone' => $zone,
                            'skeletonPointKey' => $skeletonPointKey,
                        ];
                    }
                }

                foreach ($this->users as $user) {
                    $client = $user->getClient();
                    $client->triggerEvent(
                        ClientEvent::BATTLE_UNIT_ENDS_SKILL_CASTING,
                        [
                            'initiator' => [
                                'id' => $initiator->getId(),
                            ],
                            'target' => [
                                'id' => $skillTarget->getId(),
                            ],
                            'skillCode' => $initiatorSkill->getCode(),
                            'specialEffects' => $returnData,
                        ]
                    );
                }

                // spell hit timer ends when user actually hits the target with the skill
                Loop::addTimer(
                    $hitCastDiff,
                    function () use ($initiator, $initiatorSkill, $skillTarget, $friendTarget, $enemyTarget) {
                        $this->processSkillUsage($initiator, $initiatorSkill, $skillTarget, $friendTarget, $enemyTarget);
                    }
                );
            }
        );
        $initiator->setCastingTimer($castingTimer);

        // animation timer that ends when unit completely finishes skill casting animation
        // normally should be longer as casting time because of additional "return back" animation
        $animationTimer = Loop::addTimer(
            $animationTime,
            function () use ($initiator, $initiatorSkill, $skillTarget, $friendTarget, $enemyTarget) {
                $initiator->setAnimationTimer(null);
            }
        );
        $initiator->setAnimationTimer($animationTimer);

        foreach ($this->users as $user) {
            $client = $user->getClient();
            $client->triggerEvent(
                ClientEvent::BATTLE_UNIT_STARTS_SKILL_ANIMATION,
                [
                    'initiator' => [
                        'id' => $initiator->getId(),
                        'data' => BattleUtility::prepareBattleUnitDataForResponse($initiator)
                    ],
                    'target' => [
                        'id' => $skillTarget->getId(),
                        'data' => BattleUtility::prepareBattleUnitDataForResponse($skillTarget)
                    ],
                    'skillCode' => $initiatorSkill->getCode(),
                    'animationChain' => $initiatorSkill->getAnimationChain(),
                ]
            );
        }
    }

    /**
     * @param BattleUnit $initiator
     * @param BattleSkill $skill
     * @param BattleUnit $skillTarget
     * @param BattleUnit|null $friendTarget
     * @param BattleUnit|null $enemyTarget
     *
     * @return void
     */
    protected function processSkillUsage(
        BattleUnit $initiator,
        BattleSkill $skill,
        BattleUnit $skillTarget,
        ?BattleUnit $friendTarget = null,
        ?BattleUnit $enemyTarget = null
    ) {
        try {
            $skillUsageResults = [
                'units' => [
                    $initiator->getId() => [],
                    $skillTarget->getId() => [],
                ],
                'skillCode' => $skill->getCode(),
            ];

            // IF case only to have a possibility to collapse code blocks, no logic sense
            // calculate damage
            $canBeDamaged = true;
            if ($skillTarget->getEffect(Consts\Effect::EFFECT_TYPE_DIRECT_DAMAGE_IMMUNE)) {
                $canBeDamaged = false;
            }
            if ($canBeDamaged) {
                $damageSources = $skill->getDamageSources();

                foreach ($damageSources as $damageSource) {
                    $damage = BattleUtility::calculateDamageSourceDamage($initiator, $skillTarget, $damageSource);
                    if ($damage > 0) {
                        $skillTarget->changeHp(-$damage);
                        $skillUsageResults['units'][$skillTarget->getId()]['damageReceived'][] = [
                            'type' => $damageSource->getType(),
                            'value' => $damage,
                        ];
                    }
                }
            }

            // calculate healing
            if (true) {
                $healSources = $skill->getHealSources();

                foreach ($healSources as $healSource) {
                    $heal = BattleUtility::calculateHealSourceDamage($initiator, $skillTarget, $healSource);
                    if ($heal > 0) {
                        $skillTarget->changeHp($heal);
                        $skillUsageResults['units'][$skillTarget->getId()]['healReceived'][] = [
                            'value' => $heal,
                        ];
                    }
                }
            }

            // trigger buffs
            if (true) {
                $skillBuffs = $skill->getBuffSources();

                /** @var \Server\Game\Battle\BattleBuffSource $buffSource */
                foreach ($skillBuffs as $buffCode => $buffSource) {
                    $buff = BattleBuffFactory::createBattleBuffFromBattleBuffSource($buffSource, $initiator);
                    $buffTarget = null;

                    if ($buffSource->getTargetType() === Consts\Target::TARGET_TYPE_ENEMY) {
                        if ($enemyTarget->getTeam() === $initiator->getTeam()) {
                            throw new \Exception('Only enemy target is allowed for this buff: ' . $buffSource->getCode());
                        }
                        $buffTarget = $enemyTarget;
                    }

                    if ($buffSource->getTargetType() === Consts\Target::TARGET_TYPE_FRIEND) {
                        if ($friendTarget->getTeam() !== $initiator->getTeam()) {
                            throw new \Exception('Only friend target is allowed for this buff:' . $buffSource->getCode());
                        }
                        $buffTarget = $friendTarget;
                    }

                    if ($buffSource->getTargetType() === Consts\Target::TARGET_TYPE_SELF) {
                        $buffTarget = $initiator;
                    }

                    if (!$buffTarget) {
                        throw new \Exception('Could not detect buff target for the buff: ' . $buffSource->getCode());
                    }

                    $buffCanBeApplied = true;
                    if ($buffSource->getImpactType() === Consts\Buff::IMPACT_TYPE_HARMFUL
                        && $buffTarget->getEffect(Consts\Effect::EFFECT_TYPE_HARMFUL_BUFF_APPLY_IMMUNE)
                    ) {
                        $buffCanBeApplied = false;
                    }

                    if ($buffCanBeApplied) {
                        $buffTarget->addBuff($buff->getCode(), $buff);

                        $skillUsageResults['units'][$buffTarget->getId()] = array_replace_recursive(
                            $skillUsageResults['units'][$buffTarget->getId()] ?? [],
                            BattleUtility::prepareBattleUnitDataForResponse($buffTarget)
                        );
                    }
                }
            }

            $skillUsageResults['units'][$initiator->getId()] = array_replace_recursive(
                $skillUsageResults['units'][$initiator->getId()],
                BattleUtility::prepareBattleUnitDataForResponse($initiator)
            );

            $skillUsageResults['units'][$skillTarget->getId()] = array_replace_recursive(
                $skillUsageResults['units'][$skillTarget->getId()],
                BattleUtility::prepareBattleUnitDataForResponse($skillTarget)
            );

            foreach ($this->users as $user) {
                $client = $user->getClient();
                $client->triggerEvent(
                    ClientEvent::BATTLE_UNIT_USES_SKILL,
                    $skillUsageResults
                );
            }
        } catch (\Throwable $error) {
            App()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);
            foreach ($this->users as $user) {
                $client = $user->getClient();
                $client->triggerEvent(
                    ClientEvent::ERROR_GENERAL,
                    [
                        'title' => '',
                        'message' => 'Error during skill processing',
                    ]
                );
            }
        }
    }

    protected function interruptUnitAction(BattleUnit $unit)
    {
        if ($unit->getCastingTimer()) {
            Loop::cancelTimer($unit->getCastingTimer());
            $unit->setCastingTimer(null);
        }

        if ($unit->getAnimationTimer()) {
            Loop::cancelTimer($unit->getAnimationTimer());
            $unit->setAnimationTimer(null);
        }
    }

    /**
     * @return void
     */
    protected function clear(): void
    {
        App()->getWorld()->removeBattle($this);

        /** @var User $user */
        foreach ($this->users as $user) {
            if ($this === $user->getCurrentBattle()) {
                $user->setCurrentBattle(null);
                $user->setCurrentBattlePet(null);
            }
        }
    }

    /**
     * @return Promise
     */
    public function onBattleEnd(): Promise
    {
        $this->battleEndDeferred->promise();

        return $this->battleEndDeferred->promise();
    }

    protected function generateResults()
    {
        $battleResults = new BattleResults();
        $team1 = $this->getTeam1();
        $team2 = $this->getTeam2();

        $team1Hp = $team1->getTeamHp();
        $team2Hp = $team2->getTeamHp();

        if ($team1Hp > 0) {
            $battleResults->setWinner($team1);
        } elseif ($team2Hp > 0) {
            $battleResults->setWinner($team2);
        } else {
            //draw
        }

        $this->battleEndDeferred->resolve($battleResults);

        $this->clear();
    }
}