<?php

namespace Server\Events\Subscribers;

use Psr\Log\LogLevel;
use Server\App;
use Server\Events\BaseEvent;
use Server\Events\ClientEvent;
use Server\Game\Consts;
use Server\Game\Managers\PetManager;
use Server\Game\Managers\UserManager;
use Server\Game\Models\Elixir;
use Server\Game\Models\LootItem;
use Server\Game\Models\Pet;
use Server\Game\Models\Skill;
use Server\Game\Models\Structure;
use Server\Game\Models\Talent;
use Server\Game\Utility\ElixirUtility;
use Server\Game\Utility\StructureUtility;
use Server\Game\Utility\TranslationsUtility;
use Server\Game\Utility\UnitUtility;
use Server\Game\Utility\UserUtility;

class UserClicksActivateTalentBtnEventSubscriber extends BaseSubscriber
{

    public static function getSubscribedEvents()
    {
        return [
            'UserClicksActivateTalentBtnEvent' => 'handle',
        ];
    }

    public function handle(BaseEvent $event)
    {
        parent::handle($event);

        $app = $event->getApp();
        $data = $event->getData();
        $user = $event->getClient()->getUser();
        $client = $user->getClient();

        try {
            $structureCode = $data['structureCode'] ?? null;
            $talentCode = $data['talentCode'] ?? null;
            $petCode = $data['petCode'] ?? null;
            $slot = $data['slot'] ?? null;

            $structure = $app->getWorld()->getStructures()[$structureCode] ?? null;

            if ($structure->getLocationCode() !== $user->getLocationCode()) {
                throw new \Exception('Requested structure ' . $structureCode . ' is located in another location!');
            }

            $pet = $user->getSelectedPetByCode($petCode);

            if (empty($pet)) {
                throw new \Exception('Wrong pet code is given!', 556376650);
            }

            $freeTalentPoints = UnitUtility::getPetFreeTalentPoints($pet);
            $talent = App()->getWorld()->getTalents()[$talentCode] ?? null;

            if ($freeTalentPoints < (int)$talent->getCost()) {
                throw new \Exception('No available talent points!', 556376657);
            }

            $talents = $pet->getTalents();

            if (isset($talents[$talentCode])) {
                throw new \Exception('Talent is already activated!', 556376658);
            }

            if (!UnitUtility::isPetTalentDependencyRequirementsMet($pet, $talent)) {
                throw new \Exception('Talent dependency requirements are not met!', 556376659);
            }

            PetManager::activateTalent($pet, $talent)
                ->then(
                    function () use ($structure, $pet, $talent) {
                        $this->triggerSuccessResponse($structure, $pet, $talent);
                    },
                    function (\Throwable $error) {
                        $this->triggerError($error);
                    }
                )
                ->otherwise(function (\Throwable $error) {
                    $this->triggerError($error);
                })
                ->done();
        } catch (\Throwable $error) {
            $this->triggerError($error, ClientEvent::ERROR_GENERAL);
        }
    }

    /**
     * @param Structure $structure
     * @param Pet $pet
     * @param Talent $talent
     * @return void
     * @throws \Exception
     */
    protected function triggerSuccessResponse(Structure $structure, Pet $pet, Talent $talent)
    {
        $client = $this->event->getClient();
        $user = $client->getUser();
        $response = UnitUtility::preparePetTalentsDataForResponse($pet, $user);
        $response['message'] = TranslationsUtility::getTranslation(
            'BARRACKS.ACTIVATE_TALENT.SUCCESS_MESSAGE',
            TranslationsUtility::TYPE_GENERAL,
            (string)$user->getLang(),
            [
                TranslationsUtility::getTranslation($talent->getName(), TranslationsUtility::TYPE_TALENTS, (string)$user->getLang())
            ]
        );

        $client->triggerEvent(
            ClientEvent::PLAYER_ACTIVATES_TALENT,
            $response
        );
    }

    /**
     * @param \Throwable $error
     * @param string|null $eventCode
     *
     * @return void
     */
    protected function triggerError(\Throwable $error, ?string $eventCode = null)
    {
        $this->event->getApp()->getLogger()->log($error->getMessage(), $error->getTrace(), LogLevel::ERROR);

        $client = $this->event->getClient();
        $client->triggerEvent(
            $eventCode ?: ClientEvent::ERROR_GENERAL,
            [
                'title' => '',
                'message' => 'Error: can not clear elixir!',
            ]
        );
    }
}