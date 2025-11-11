<?php

declare(strict_types=1);

namespace Server\Game\Models;

/**
 *
 */
class Unit extends BaseModel
{
    /**
     * @var string|null
     */
    protected ?string $code;

    /**
     * @var string|null
     */
    protected ?string $name;

    /**
     * @var string|null
     */
    protected ?string $sprite;

    /**
     * @var array|null
     */
    protected ?array $animations;

    /**
     * @var array|null
     */
    protected ?array $skeletonPoints;

    /**
     * @var float|null
     */
    protected ?float $baseHp;

    /**
     * @var float|null
     */
    protected ?float $hpPerLvl;

    /**
     * @var float|null
     */
    protected ?float $hpRegen;

    /**
     * @var float|null
     */
    protected ?float $hpRegenPerLvl;

    /**
     * @var float|null
     */
    protected ?float $baseMp;

    /**
     * @var float|null
     */
    protected ?float $mpPerLvl;

    /**
     * @var float|null
     */
    protected ?float $mpRegen;

    /**
     * @var float|null
     */
    protected ?float $mpRegenPerLvl;

    /**
     * @var float|null
     */
    protected ?float $baseAttack;

    /**
     * @var float|null
     */
    protected ?float $baseAttackPerLvl;

    /**
     * @var float|null
     */
    protected ?float $baseAttackSpeed;

    /**
     * @var float|null
     */
    protected ?float $baseAttackSpeedPerLvl;

    /**
     * @var float|null
     */
    protected ?float $physicalDefence;

    /**
     * @var float|null
     */
    protected ?float $physicalDefencePerLvl;

    /**
     * @var float|null
     */
    protected ?float $magicalDefence;

    /**
     * @var float|null
     */
    protected ?float $magicalDefencePerLvl;

    /**
     * @var float|null
     */
    protected ?float $attackCritChance;

    /**
     * @var float|null
     */
    protected ?float $attackCritDamage;

    /**
     * @var float|null
     */
    protected ?float $attackCritChancePerLvl;

    /**
     * @var float|null
     */
    protected ?float $attackCritDamagePerLvl;

    /**
     * @var float|null
     */
    protected ?float $abilityCritChance;

    /**
     * @var float|null
     */
    protected ?float $abilityCritDamage;

    /**
     * @var float|null
     */
    protected ?float $abilityCritChancePerLvl;

    /**
     * @var float|null
     */
    protected ?float $abilityCritDamagePerLvl;

    /**
     * @var float|null
     */
    protected ?float $dodgeChance;

    /**
     * @var float|null
     */
    protected ?float $dodgeChancePerLvl;

    /**
     * @var float|null
     */
    protected ?float $physicalDamageIncrease;

    /**
     * @var float|null
     */
    protected ?float $physicalDamageIncreasePerLvl;

    /**
     * @var float|null
     */
    protected ?float $magicalDamageIncrease;

    /**
     * @var float|null
     */
    protected ?float $magicalDamageIncreasePerLvl;

    /**
     * @var array|null
     */
    protected ?array $skillCodes;

    /**
     * @var array|null
     */
    protected ?array $skills;

    /**
     * @var array|null
     */
    protected ?array $appliedElixirCodes;

    /**
     * @var array|null
     */
    protected ?array $appliedElixirs;

    /**
     * @var array|null
     */
    protected ?array $talentCategories;

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param  string|null  $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param  string|null  $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getSprite(): ?string
    {
        return $this->sprite;
    }

    /**
     * @param  string|null  $sprite
     */
    public function setSprite(?string $sprite): void
    {
        $this->sprite = $sprite;
    }

    /**
     * @return array|null
     */
    public function getAnimations(): ?array
    {
        return $this->animations;
    }

    /**
     * @param array|null $animations
     */
    public function setAnimations(?array $animations): void
    {
        $this->animations = $animations;
    }

    /**
     * @return array|null
     */
    public function getSkeletonPoints(): ?array
    {
        return $this->skeletonPoints;
    }

    /**
     * @param array|null $skeletonPoints
     */
    public function setSkeletonPoints(?array $skeletonPoints): void
    {
        $this->skeletonPoints = $skeletonPoints;
    }

    /**
     * @return float|null
     */
    public function getBaseHp(): ?float
    {
        return $this->baseHp;
    }

    /**
     * @param  float|null  $baseHp
     *
     * @return void
     */
    public function setBaseHp(?float $baseHp): void
    {
        $this->baseHp = $baseHp;
    }

    /**
     * @return float|null
     */
    public function getHpPerLvl(): ?float
    {
        return $this->hpPerLvl;
    }

    /**
     * @param  float|null  $hpPerLvl
     *
     * @return void
     */
    public function setHpPerLvl(?float $hpPerLvl): void
    {
        $this->hpPerLvl = $hpPerLvl;
    }

    /**
     * @return float|null
     */
    public function getHpRegen(): ?float
    {
        return $this->hpRegen;
    }

    /**
     * @param  float|null  $hpRegen
     */
    public function setHpRegen(?float $hpRegen): void
    {
        $this->hpRegen = $hpRegen;
    }

    /**
     * @return float|null
     */
    public function getHpRegenPerLvl(): ?float
    {
        return $this->hpRegenPerLvl;
    }

    /**
     * @param  float|null  $hpRegenPerLvl
     */
    public function setHpRegenPerLvl(?float $hpRegenPerLvl): void
    {
        $this->hpRegenPerLvl = $hpRegenPerLvl;
    }

    /**
     * @return float|null
     */
    public function getBaseMp(): ?float
    {
        return $this->baseMp;
    }

    /**
     * @param  float|null  $baseMp
     *
     * @return void
     */
    public function setBaseMp(?float $baseMp): void
    {
        $this->baseMp = $baseMp;
    }

    /**
     * @return float|null
     */
    public function getMpPerLvl(): ?float
    {
        return $this->mpPerLvl;
    }

    /**
     * @param  float|null  $mpPerLvl
     *
     * @return void
     */
    public function setMpPerLvl(?float $mpPerLvl): void
    {
        $this->mpPerLvl = $mpPerLvl;
    }

    /**
     * @return float|null
     */
    public function getMpRegen(): ?float
    {
        return $this->mpRegen;
    }

    /**
     * @param float|null $mpRegen
     *
     * @return void
     */
    public function setMpRegen(?float $mpRegen): void
    {
        $this->mpRegen = $mpRegen;
    }

    /**
     * @return float|null
     */
    public function getMpRegenPerLvl(): ?float
    {
        return $this->mpRegenPerLvl;
    }

    /**
     * @param float|null $mpRegenPerLvl
     *
     * @return void
     */
    public function setMpRegenPerLvl(?float $mpRegenPerLvl): void
    {
        $this->mpRegenPerLvl = $mpRegenPerLvl;
    }

    /**
     * @return float|null
     */
    public function getBaseAttack(): ?float
    {
        return $this->baseAttack;
    }

    /**
     * @param  float|null  $baseAttack
     *
     * @return void
     */
    public function setBaseAttack(?float $baseAttack): void
    {
        $this->baseAttack = $baseAttack;
    }

    /**
     * @return float|null
     */
    public function getBaseAttackPerLvl(): ?float
    {
        return $this->baseAttackPerLvl;
    }

    /**
     * @param  float|null  $baseAttackPerLvl
     *
     * @return void
     */
    public function setBaseAttackPerLvl(?float $baseAttackPerLvl): void
    {
        $this->baseAttackPerLvl = $baseAttackPerLvl;
    }

    /**
     * @return float|null
     */
    public function getBaseAttackSpeed(): ?float
    {
        return $this->baseAttackSpeed;
    }

    /**
     * @param  float|null  $baseAttackSpeed
     */
    public function setBaseAttackSpeed(?float $baseAttackSpeed): void
    {
        $this->baseAttackSpeed = $baseAttackSpeed;
    }

    /**
     * @return float|null
     */
    public function getBaseAttackSpeedPerLvl(): ?float
    {
        return $this->baseAttackSpeedPerLvl;
    }

    /**
     * @param  float|null  $baseAttackSpeedPerLvl
     */
    public function setBaseAttackSpeedPerLvl(?float $baseAttackSpeedPerLvl): void
    {
        $this->baseAttackSpeedPerLvl = $baseAttackSpeedPerLvl;
    }

    /**
     * @return float|null
     */
    public function getPhysicalDefence(): ?float
    {
        return $this->physicalDefence;
    }

    /**
     * @param  float|null  $physicalDefence
     */
    public function setPhysicalDefence(?float $physicalDefence): void
    {
        $this->physicalDefence = $physicalDefence;
    }

    /**
     * @return float|null
     */
    public function getPhysicalDefencePerLvl(): ?float
    {
        return $this->physicalDefencePerLvl;
    }

    /**
     * @param  float|null  $physicalDefencePerLvl
     */
    public function setPhysicalDefencePerLvl(?float $physicalDefencePerLvl): void
    {
        $this->physicalDefencePerLvl = $physicalDefencePerLvl;
    }

    /**
     * @return float|null
     */
    public function getMagicalDefence(): ?float
    {
        return $this->magicalDefence;
    }

    /**
     * @param  float|null  $magicalDefence
     */
    public function setMagicalDefence(?float $magicalDefence): void
    {
        $this->magicalDefence = $magicalDefence;
    }

    /**
     * @return float|null
     */
    public function getMagicalDefencePerLvl(): ?float
    {
        return $this->magicalDefencePerLvl;
    }

    /**
     * @param  float|null  $magicalDefencePerLvl
     */
    public function setMagicalDefencePerLvl(?float $magicalDefencePerLvl): void
    {
        $this->magicalDefencePerLvl = $magicalDefencePerLvl;
    }

    /**
     * @return float|null
     */
    public function getAttackCritChance(): ?float
    {
        return $this->attackCritChance;
    }

    /**
     * @param float|null $attackCritChance
     */
    public function setAttackCritChance(?float $attackCritChance): void
    {
        $this->attackCritChance = $attackCritChance;
    }

    /**
     * @return float|null
     */
    public function getAttackCritDamage(): ?float
    {
        return $this->attackCritDamage;
    }

    /**
     * @param float|null $attackCritDamage
     */
    public function setAttackCritDamage(?float $attackCritDamage): void
    {
        $this->attackCritDamage = $attackCritDamage;
    }

    /**
     * @return float|null
     */
    public function getAttackCritChancePerLvl(): ?float
    {
        return $this->attackCritChancePerLvl;
    }

    /**
     * @param float|null $attackCritChancePerLvl
     */
    public function setAttackCritChancePerLvl(?float $attackCritChancePerLvl): void
    {
        $this->attackCritChancePerLvl = $attackCritChancePerLvl;
    }

    /**
     * @return float|null
     */
    public function getAttackCritDamagePerLvl(): ?float
    {
        return $this->attackCritDamagePerLvl;
    }

    /**
     * @param float|null $attackCritDamagePerLvl
     */
    public function setAttackCritDamagePerLvl(?float $attackCritDamagePerLvl): void
    {
        $this->attackCritDamagePerLvl = $attackCritDamagePerLvl;
    }

    /**
     * @return float|null
     */
    public function getAbilityCritChance(): ?float
    {
        return $this->abilityCritChance;
    }

    /**
     * @param float|null $abilityCritChance
     */
    public function setAbilityCritChance(?float $abilityCritChance): void
    {
        $this->abilityCritChance = $abilityCritChance;
    }

    /**
     * @return float|null
     */
    public function getAbilityCritDamage(): ?float
    {
        return $this->abilityCritDamage;
    }

    /**
     * @param float|null $abilityCritDamage
     */
    public function setAbilityCritDamage(?float $abilityCritDamage): void
    {
        $this->abilityCritDamage = $abilityCritDamage;
    }

    /**
     * @return float|null
     */
    public function getAbilityCritChancePerLvl(): ?float
    {
        return $this->abilityCritChancePerLvl;
    }

    /**
     * @param float|null $abilityCritChancePerLvl
     */
    public function setAbilityCritChancePerLvl(?float $abilityCritChancePerLvl): void
    {
        $this->abilityCritChancePerLvl = $abilityCritChancePerLvl;
    }

    /**
     * @return float|null
     */
    public function getAbilityCritDamagePerLvl(): ?float
    {
        return $this->abilityCritDamagePerLvl;
    }

    /**
     * @param float|null $abilityCritDamagePerLvl
     */
    public function setAbilityCritDamagePerLvl(?float $abilityCritDamagePerLvl): void
    {
        $this->abilityCritDamagePerLvl = $abilityCritDamagePerLvl;
    }

    /**
     * @return float|null
     */
    public function getDodgeChance(): ?float
    {
        return $this->dodgeChance;
    }

    /**
     * @param float|null $dodgeChance
     */
    public function setDodgeChance(?float $dodgeChance): void
    {
        $this->dodgeChance = $dodgeChance;
    }

    /**
     * @return float|null
     */
    public function getDodgeChancePerLvl(): ?float
    {
        return $this->dodgeChancePerLvl;
    }

    /**
     * @param float|null $dodgeChancePerLvl
     */
    public function setDodgeChancePerLvl(?float $dodgeChancePerLvl): void
    {
        $this->dodgeChancePerLvl = $dodgeChancePerLvl;
    }

    /**
     * @return float|null
     */
    public function getPhysicalDamageIncrease(): ?float
    {
        return $this->physicalDamageIncrease;
    }

    /**
     * @param float|null $physicalDamageIncrease
     */
    public function setPhysicalDamageIncrease(?float $physicalDamageIncrease): void
    {
        $this->physicalDamageIncrease = $physicalDamageIncrease;
    }

    /**
     * @return float|null
     */
    public function getPhysicalDamageIncreasePerLvl(): ?float
    {
        return $this->physicalDamageIncreasePerLvl;
    }

    /**
     * @param float|null $physicalDamageIncreasePerLvl
     */
    public function setPhysicalDamageIncreasePerLvl(?float $physicalDamageIncreasePerLvl): void
    {
        $this->physicalDamageIncreasePerLvl = $physicalDamageIncreasePerLvl;
    }

    /**
     * @return float|null
     */
    public function getMagicalDamageIncrease(): ?float
    {
        return $this->magicalDamageIncrease;
    }

    /**
     * @param float|null $magicalDamageIncrease
     */
    public function setMagicalDamageIncrease(?float $magicalDamageIncrease): void
    {
        $this->magicalDamageIncrease = $magicalDamageIncrease;
    }

    /**
     * @return float|null
     */
    public function getMagicalDamageIncreasePerLvl(): ?float
    {
        return $this->magicalDamageIncreasePerLvl;
    }

    /**
     * @param float|null $magicalDamageIncreasePerLvl
     */
    public function setMagicalDamageIncreasePerLvl(?float $magicalDamageIncreasePerLvl): void
    {
        $this->magicalDamageIncreasePerLvl = $magicalDamageIncreasePerLvl;
    }

    /**
     * @return array|null
     */
    public function getSkillCodes(): ?array
    {
        return $this->skillCodes;
    }

    /**
     * @param  array|null  $skillCodes
     */
    public function setSkillCodes(?array $skillCodes): void
    {
        $this->skillCodes = $skillCodes;
    }

    /**
     * @return array|null
     */
    public function getSkills(): ?array
    {
        return $this->skills;
    }

    /**
     * @param  array|null  $skills
     */
    public function setSkills(?array $skills): void
    {
        $this->skills = $skills;
    }

    /**
     * @return array|null
     */
    public function getAppliedElixirCodes(): ?array
    {
        return $this->appliedElixirCodes;
    }

    /**
     * @param array|null $appliedElixirCodes
     */
    public function setAppliedElixirCodes(?array $appliedElixirCodes): void
    {
        $this->appliedElixirCodes = $appliedElixirCodes;
    }

    /**
     * @return array|null
     */
    public function getAppliedElixirs(): ?array
    {
        return $this->appliedElixirs;
    }

    /**
     * @param array|null $appliedElixirs
     */
    public function setAppliedElixirs(?array $appliedElixirs): void
    {
        $this->appliedElixirs = $appliedElixirs;
    }

    /**
     * @param Elixir $elixir
     * @return void
     */
    public function addAppliedElixir(Elixir $elixir): void
    {
        $this->appliedElixirs[$elixir->getCode()] ??= $elixir;
    }

    /**
     * @param int $slot
     * @return Elixir|null
     */
    public function getAppliedElixirBySlot(int $slot): ?Elixir
    {
        $elixirCodeBySlot = $this->appliedElixirCodes[$slot] ?? null;

        if (!empty($elixirCodeBySlot) && !empty($elixir = ($this->getAppliedElixirs()[$elixirCodeBySlot] ?? null))) {
            return $elixir;
        }

        return null;
    }

    /**
     * @param int $slot
     * @return void
     */
    public function removeAppliedElixirBySlot(int $slot): void
    {
        $elixirCodeBySlot = $this->appliedElixirCodes[$slot] ?? null;

        if (!empty($elixirCodeBySlot)) {
            $this->removeAppliedElixirByCode($elixirCodeBySlot);
        }
    }

    /**
     * @param string $code
     * @return void
     */
    public function removeAppliedElixirByCode(string $code): void
    {
        unset($this->appliedElixirs[$code]);
    }

    /**
     * @return array|null
     */
    public function getTalentCategories(): ?array
    {
        return $this->talentCategories;
    }

    /**
     * @param array|null $talentCategories
     */
    public function setTalentCategories(?array $talentCategories): void
    {
        $this->talentCategories = $talentCategories;
    }
}