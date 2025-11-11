<?php

declare(strict_types=1);

namespace Server\Core\GameData;

/**
 * Class DataChecker
 *
 * @package Server\Core\GameData
 */
class DataChecker
{

    protected ?Loader $loader;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->loader = new Loader(GAME_DATA_FOLDER_PATH);
    }

    public function checkData()
    {
        $this->checkBuffs();
        $this->checkElixirs();
        $this->checkItems();
        $this->checkLocations();
        $this->checkNpcs();
        $this->checkSkills();
        $this->checkStructures();
        $this->checkUnits();
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function checkUnits(): void
    {
        $units = $this->loader->loadByDataType(DataType::Units);

        foreach ($units as $unitCode => $unitData) {
            foreach (
                [
                    'name',
                    'sprite',
                    'animations',
                    'baseHp',
                    'hpPerLvl',
                    'hpRegen',
                    'hpRegenPerLvl',
                    'baseMp',
                    'mpPerLvl',
                    'baseAttack',
                    'baseAttackPerLvl',
                    'baseAttackSpeed',
                    'baseAttackSpeedPerLvl',
                    'physicalDefence',
                    'physicalDefencePerLvl',
                    'magicalDefence',
                    'magicalDefencePerLvl',
                    'attackCritChance',
                    'attackCritDamage',
                    'attackCritChancePerLvl',
                    'attackCritDamagePerLvl',
                    'abilityCritChance',
                    'abilityCritDamage',
                    'abilityCritChancePerLvl',
                    'abilityCritDamagePerLvl',
                    'dodgeChance',
                    'dodgeChancePerLvl',
                    'physicalDamageIncrease',
                    'physicalDamageIncreasePerLvl',
                    'magicalDamageIncrease',
                    'magicalDamageIncreasePerLvl',
                    'skillCodes',
                ] as $requiredProperty
            ) {
                if (!isset($unitData[$requiredProperty])) {
                    throw new \Exception('Missing ' . $requiredProperty . ' for unit with code ' . $unitCode);
                }
            }

            if (empty($unitData['skillCodes'])) {
                throw new \Exception('Unit with no skills detected! UnitCode: ' . $unitCode);
            }
        }
    }

    protected function checkNpcs(): void
    {
        $npcs = $this->loader->loadByDataType(DataType::NPCs);

        foreach ($npcs as $npcCode => $npcData) {
            foreach (
                [
                    'npcName',
                    'npcSprite',
                    'unitCode',
                    'lvl',
                    'coordX',
                    'coordY',
                    'habitatRadius',
                    'maxAmount',
                    'chanceToBeFound',
                    'respawnTime',
                    'baseExpReward',
                    'type',
                    'positionType',
                    'rarity',
                    'locationCode',
                    'appliedElixirCodes',
                    'lootData'
                ] as $requiredProperty
            ) {
                if (!isset($npcData[$requiredProperty])) {
                    throw new \Exception('Missing ' . $requiredProperty . ' for npc with code ' . $npcCode);
                }
            }
        }
    }

    protected function checkSkills(): void
    {
        $skills = $this->loader->loadByDataType(DataType::Skills);

        foreach ($skills as $skillCode => $skillData) {
            foreach (
                [
                    'name',
                    'icon',
                    'targetType',
                    'castingTime',
                    'hitTime',
                    'animationChain',
                    'specialEffects',
                    'cooldown',
                    'cooldownPerLvl',
                    'cooldownAttackSpeedCoefficient',
                    'damageSources',
                    'healSources',
                    'mpCost',
                    'mpCostPerLvl',
                    'buffCodes',
                    'requirements'
                ] as $requiredProperty
            ) {
                if (!isset($skillData[$requiredProperty])) {
                    throw new \Exception('Missing ' . $requiredProperty . ' for skill with code ' . $skillCode);
                }
            }
        }
    }

    protected function checkLocations(): void
    {
        $locations = $this->loader->loadByDataType(DataType::Locations);

        foreach ($locations as $locationCode => $locationData) {
            foreach (
                [
                    'name',
                    'source',
                    'type',
                    'x',
                    'y',
                    'territoryCode',
                    'siblings',
                ] as $requiredProperty
            ) {
                if (!isset($locationData[$requiredProperty])) {
                    throw new \Exception('Missing ' . $requiredProperty . ' for unit with code ' . $locationCode);
                }
            }
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function checkStructures(): void
    {
        $structures = $this->loader->loadByDataType(DataType::Structures);

        foreach ($structures as $structureCode => $structureData) {
            foreach (
                [
                    'name',
                    'description',
                    'sprite',
                    'scale',
                    'type',
                    'subtype',
                    'coordX',
                    'coordY',
                    'locationCode',
                    'clickable',
                    'shopData',
                    'quickAccessData',
                ] as $requiredProperty
            ) {
                if (!isset($structureData[$requiredProperty])) {
                    throw new \Exception('Missing ' . $requiredProperty . ' for structure with code ' . $structureCode);
                }
            }
        }
    }

    protected function checkItems(): void
    {
        $items = $this->loader->loadByDataType(DataType::Items);

        foreach ($items as $itemCode => $itemData) {
            foreach (
                [
                    'name',
                    'description',
                    'type',
                    'icon',
                    'rarity',
                    'useFunction'
                ] as $requiredProperty
            ) {
                if (!isset($itemData[$requiredProperty])) {
                    throw new \Exception('Missing ' . $requiredProperty . ' for item with code ' . $itemCode);
                }
            }

            if ($itemData['type'] === \Server\Game\Consts\Item::ITEM_TYPE_ELIXIR
                && empty($itemData['elixirCode'])
            ) {
                throw new \Exception('Elixir code must be defined for the item of type Elixir! Item code: ' . $itemCode);
            }
        }
    }

    protected function checkBuffs(): void
    {
        $buffs = $this->loader->loadByDataType(DataType::Buffs);

        foreach ($buffs as $buffCode => $buffData) {
            foreach (
                [
                    'name',
                    'icon',
                    'targetType',
                    'stackingType',
                    'duration',
                    'ticks',
                    'firstTickDelay',
                    'damageSources',
                    'multipliers',
                    'effects',
                ] as $requiredProperty
            ) {
                if (!isset($buffData[$requiredProperty])) {
                    throw new \Exception('Missing ' . $requiredProperty . ' for buff with code ' . $buffCode);
                }
            }
        }
    }

    protected function checkElixirs(): void
    {
        $elixirs = $this->loader->loadByDataType(DataType::Elixirs);

        foreach ($elixirs as $elixirCode => $elixirData) {
            foreach (
                [
                    'name',
                    'description',
                    'icon',
                    'rarity',
                    'requirements',
                    'slotType',
                    'buffCodes',
                    'attributeBonuses',
                ] as $requiredProperty
            ) {
                if (!isset($elixirData[$requiredProperty])) {
                    throw new \Exception('Missing ' . $requiredProperty . ' for elixir with code ' . $elixirCode);
                }
            }
        }
    }
}