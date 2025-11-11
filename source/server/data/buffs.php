<?php

use Server\Game\Consts;

return [
    'B00001' => [
        'name' => 'OPEN_WOUNDS',
        'icon' => 'open_wounds',
        'type' => Consts\Buff::TYPE_COMMON,
        'visibilityType' => Consts\Buff::VISIBILITY_TYPE_VISIBLE,
        'targetType' => Consts\Target::TARGET_TYPE_ENEMY,
        'stackingType' => Consts\Buff::STACKING_TYPE_STACKABLE,
        'impactType' => Consts\Buff::IMPACT_TYPE_HARMFUL,
        'duration' => 10,
        'ticks' => 5,
        'firstTickDelay' => 2,
        'damageSources' => [
            [
                'type' => Consts\Damage::DAMAGE_TYPE_PHYSICAL,
                'damage' => 200,
                'damagePerLvl' => 5,
                'attackCoefficient' => 0,
                'attackCoefficientPerLvl' => 0
            ]
        ],
        'multipliers' => [],
        'effects' => []
    ],
    'B00002' => [
        'name' => 'MAGIC_BEAM',
        'icon' => 'magic_beam',
        'type' => Consts\Buff::TYPE_COMMON,
        'visibilityType' => Consts\Buff::VISIBILITY_TYPE_VISIBLE,
        'targetType' => Consts\Target::TARGET_TYPE_ENEMY,
        'stackingType' => Consts\Buff::STACKING_TYPE_UNIQUE_PER_SOURCE,
        'impactType' => Consts\Buff::IMPACT_TYPE_HARMFUL,
        'duration' => 6,
        'ticks' => 3,
        'firstTickDelay' => 2,
        'damageSources' => [
            [
                'type' => Consts\Damage::DAMAGE_TYPE_MAGICAL,
                'damage' => 45,
                'damagePerLvl' => 10,
                'attackCoefficient' => 0,
                'attackCoefficientPerLvl' => 0
            ]
        ],
        'multipliers' => [],
        'effects' => []
    ],
    'B00003' => [
        'name' => 'NATURE_BLESSING',
        'icon' => 'natureblessing',
        'type' => Consts\Buff::TYPE_COMMON,
        'visibilityType' => Consts\Buff::VISIBILITY_TYPE_VISIBLE,
        'targetType' => Consts\Target::TARGET_TYPE_SELF,
        'stackingType' => Consts\Buff::STACKING_TYPE_UNIQUE,
        'impactType' => Consts\Buff::IMPACT_TYPE_POSITIVE,
        'duration' => 12,
        'ticks' => 12,
        'firstTickDelay' => 0,
        'damageSources' => [],
        'multipliers' => [
            [
                'type' => Consts\Unit::UNIT_STAT_MAX_HP,
                'multiplier' => 0.3,
                'multiplierPerLvl' => 0,
            ]
        ],
        'effects' => []
    ],
    'B00004' => [
        'name' => 'MAGIC_BEAM_STUN',
        'icon' => 'magic_beam',
        'type' => Consts\Buff::TYPE_COMMON,
        'visibilityType' => Consts\Buff::VISIBILITY_TYPE_VISIBLE,
        'targetType' => Consts\Target::TARGET_TYPE_ENEMY,
        'stackingType' => Consts\Buff::STACKING_TYPE_UNIQUE_PER_SOURCE,
        'impactType' => Consts\Buff::IMPACT_TYPE_HARMFUL,
        'duration' => 1,
        'ticks' => 1,
        'firstTickDelay' => 0,
        'damageSources' => [],
        'multipliers' => [],
        'effects' => [
            [
                'type' => Consts\Effect::EFFECT_TYPE_STUN,
            ]
        ]
    ],
    'B00005' => [
        'name' => 'POWER_INCREASE_TALENT_A00001',
        'icon' => '',
        'type' => Consts\Buff::TYPE_PERMANENT,
        'visibilityType' => Consts\Buff::VISIBILITY_TYPE_HIDDEN,
        'targetType' => Consts\Target::TARGET_TYPE_SELF,
        'stackingType' => Consts\Buff::STACKING_TYPE_UNIQUE,
        'impactType' => Consts\Buff::IMPACT_TYPE_POSITIVE,
        'duration' => 99999,
        'ticks' => 1,
        'firstTickDelay' => 0,
        'damageSources' => [],
        'multipliers' => [
            [
                'type' => Consts\Unit::UNIT_STAT_ATTACK,
                'multiplier' => 0.02,
                'multiplierPerLvl' => 0,
            ]
        ],
        'effects' => []
    ],
    'B00006' => [
        'name' => 'BLOCK',
        'icon' => 'defense_main',
        'type' => Consts\Buff::TYPE_COMMON,
        'visibilityType' => Consts\Buff::VISIBILITY_TYPE_VISIBLE,
        'targetType' => Consts\Target::TARGET_TYPE_SELF,
        'stackingType' => Consts\Buff::STACKING_TYPE_UNIQUE,
        'impactType' => Consts\Buff::IMPACT_TYPE_POSITIVE,
        'duration' => 0.8,
        'ticks' => 1,
        'firstTickDelay' => 0,
        'damageSources' => [],  
        'multipliers' => [],      
        'effects' => [
            [
                'type' => Consts\Effect::EFFECT_TYPE_DIRECT_DAMAGE_IMMUNE,
            ],
            [
                'type' => Consts\Effect::EFFECT_TYPE_HARMFUL_BUFF_APPLY_IMMUNE,
            ],
        ]
    ],
];