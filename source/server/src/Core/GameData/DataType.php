<?php

declare(strict_types=1);

namespace Server\Core\GameData;

/**
 * Class DataType
 *
 * @package Server\Core\GameData
 */
enum DataType
{
    case ExpTable;
    case Units;
    case NPCs;
    case Skills;
    case Buffs;
    case Locations;
    case Territories;
    case Elixirs;
    case Structures;
    case Items;

    case Talents;
}