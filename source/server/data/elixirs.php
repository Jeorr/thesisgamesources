<?php

use Server\Game\Consts;

return [
    'E00001' => [
        'name' => 'LIGHT_ELIXIR_OF_PHYSICAL_POWER_NAME',
        'description' => 'LIGHT_ELIXIR_OF_PHYSICAL_POWER_DESCRIPTION',
        'icon' => 'attack',
        'rarity' => \Server\Game\Consts\Item::ITEM_RARITY_GOOD,
        'requirements' => [
            [
                'type' => Consts\Elixir::REQUIREMENT_TYPE_LEVEL,
                'value' => 1
            ],
        ],
        'slotType' => Consts\Elixir::SLOT_TYPE_OFFENSIVE,
        'buffCodes' => [],
        'attributeBonuses' => [
            [
                'type' => Consts\Unit::UNIT_STAT_HP,
                'static' => 2000,
                'multiplier' => 0.3,
            ]
        ],
    ],
    'E00002' => [
        'name' => 'ELIXIR_OF_LOW_CRIT_CHANCE_NAME',
        'description' => 'ELIXIR_OF_LOW_CRIT_CHANCE_DESCRIPTION',
        'icon' => '15',
        'rarity' => \Server\Game\Consts\Item::ITEM_RARITY_GOOD,
        'requirements' => [
            [
                'type' => Consts\Elixir::REQUIREMENT_TYPE_LEVEL,
                'value' => 10
            ],
        ],
        'slotType' => Consts\Elixir::SLOT_TYPE_OFFENSIVE,
        'buffCodes' => [],
        'attributeBonuses' => [
            [
                'type' => Consts\Unit::UNIT_STAT_ATTACK,
                'static' => 100,
                'multiplier' => 0.1,
            ]
        ],
    ],
];