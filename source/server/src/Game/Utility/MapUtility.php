<?php

declare(strict_types=1);

namespace Server\Game\Utility;

use Server\Game\Battle\Factories\BattleUnitFactory;
use Server\Game\Consts\Npc;
use Server\Game\Models\Location;
use Server\Game\Models\Structure;
use Server\Game\Models\User;

/**
 *
 */
class MapUtility
{

    /**
     * @param  \Server\Game\Models\User  $user
     *
     * @return array
     * @throws \Exception
     */
    public static function collectCurrentTerritoryDataForResponse(User $user): array
    {
        $data = [];
        $currentLocation = $user->getLocation();
        $currentTerritory = $currentLocation->getTerritory();
        $territoryLocations = $currentTerritory->getLocations();

        $data['currentTerritory'] = [
            'code' => $currentTerritory->getCode(),
            'name' => TranslationsUtility::getTranslation($currentTerritory->getName(), TranslationsUtility::TYPE_GENERAL, (string)$user->getLang()),
            'source' => $currentTerritory->getSource(),
        ];
        $data['currentLocation'] = [
            'code' => $currentLocation->getCode(),
            'name' => TranslationsUtility::getTranslation($currentLocation->getName(), TranslationsUtility::TYPE_GENERAL, (string)$user->getLang()),
            'x' => $currentLocation->getX(),
            'y' => $currentLocation->getY(),
            'siblings' => $currentLocation->getSiblings(),
        ];

        foreach ($territoryLocations as $location) {
            $data['locations'][] = [
                'code' => $location->getCode(),
                'name' => TranslationsUtility::getTranslation($location->getName(), TranslationsUtility::TYPE_GENERAL, (string)$user->getLang()),
                'x' => $location->getX(),
                'y' => $location->getY(),
                'siblings' => $location->getSiblings(),
            ];
        }

        return $data;
    }

    /**
     * @param User $user
     * @param Location $location
     * @return array|array[]
     * @throws \Exception
     */
    public static function collectLocationDetailsDataForResponse(User $user, Location $location): array
    {
        $data = [
            'location' => [
                'code' => $location->getCode(),
                'type' => $location->getType(),
                'source' => $location->getSource(),
            ],
        ];

        /** @var  \Server\Game\Models\Npc $npc */
        foreach ($location->getNpcs() as $npcCode => $npc) {
            if ($npc->getPositionType() !== Npc::POSITION_TYPE_STATIC) {
                continue;
            }

            $data['location']['npcs'][$npcCode] = [
                'code' => $npcCode,
                'type' => $npc->getType(),
                'name' => TranslationsUtility::getTranslation($npc->getNpcName(), TranslationsUtility::TYPE_GENERAL, (string)$user->getLang()),
                'sprite' => $npc->getNpcSprite(),
                'x' => $npc->getCoordX(),
                'y' => $npc->getCoordY()
            ];
        }

        /** @var Structure $structure */
        foreach ($location->getStructures() as $code => $structure) {
            $quickAccessData = $structure->getQuickAccessData();
            $quickAccessData['label'] = TranslationsUtility::getTranslation(
                $structure->getName(), 
                TranslationsUtility::TYPE_STRUCTURES, 
                (string)$user->getLang()
            );

            $data['location']['structures'][$code] = [
                'code' => $code,
                'type' => $structure->getType(),
                'subtype' => $structure->getSubtype(),
                'name' => TranslationsUtility::getTranslation($structure->getName(), TranslationsUtility::TYPE_STRUCTURES, (string)$user->getLang()),
                'description' => TranslationsUtility::getTranslation($structure->getDescription(), TranslationsUtility::TYPE_STRUCTURES, (string)$user->getLang()),
                'sprite' => $structure->getSprite(),
                'scale' => $structure->getScale(),
                'clickable' => $structure->getClickable(),
                'quickAccessData' => $quickAccessData,
                'x' => $structure->getCoordX(),
                'y' => $structure->getCoordY(),
                'meta' => $structure->getMeta(),
            ];
        }

        return $data;
    }

    /**
     * @param \Server\Game\Models\User $user
     * @param array $foundNpcsData
     *
     * @return array
     * @throws \Exception
     */
    public static function collectInvestigatedPointDataForResponse(User $user, array $foundNpcsData): array
    {
        $data = [];

        /**
         * @var  $npcCode
         * @var  \Server\Game\Models\Npc $npc
         */
        foreach ($foundNpcsData as $npcCode => $npc) {
            $battleUnit = BattleUnitFactory::createUnitFromLegacyModel($npc);

            $data['npcs'][$npcCode] = [
                'code' => $npcCode,
                'type' => $npc->getType(),
                'name' => TranslationsUtility::getTranslation($npc->getNpcName(), TranslationsUtility::TYPE_GENERAL, $user->getLang()),
                'sprite' => $npc->getNpcSprite(),
                'level' => $npc->getLvl(),
                'stats' => [
                    [
                        'name' => 'ATTR_HP',
                        'value' => $battleUnit->getHp(),
                    ],
                    [
                        'name' => 'ATTR_MP',
                        'value' => $battleUnit->getMp(),
                    ],
                    [
                        'name' => 'ATTR_ATTACK',
                        'value' => $battleUnit->getAttack(),
                    ],
                    [
                        'name' => 'ATTR_ATTACK_SPEED',
                        'value' => $battleUnit->getAttackSpeed(),
                    ],
                    [
                        'name' => 'ATTR_PHYSICAL_DEFENCE',
                        'value' => $battleUnit->getPhysicalDefence(),
                    ],
                    [
                        'name' => 'ATTR_MAGICAL_DEFENCE',
                        'value' => $battleUnit->getMagicalDefence(),
                    ],
                ],
            ];
        }

        return $data;
    }

    public static function investigateLocationCoordinates(Location $location, int $x, int $y)
    {
        $foundNpcs = [];
        $locationNpcs = $location->getNpcs();

        foreach ($locationNpcs as $npc) {
            if ($npc->getPositionType() !== Npc::POSITION_TYPE_DYNAMIC) {
                continue;
            }
            $npcX = $npc->getCoordX();
            $npcY = $npc->getCoordY();
            $distance = sqrt(pow($x - $npcX, 2) + pow($y - $npcY, 2));
            $habitatRadius = $npc->getHabitatRadius();

            if ($distance <= $habitatRadius) {
                $foundNpcs[$npc->getCode()] = $npc;
            }
        }

        return $foundNpcs;
    }
}