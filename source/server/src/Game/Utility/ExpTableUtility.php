<?php

declare(strict_types=1);

namespace Server\Game\Utility;

use Server\Core\GameData\DataType;
use Server\Core\GameData\Loader;

/**
 *
 */
class ExpTableUtility
{

    /**
     * @var array|null
     */
    protected static ?array $expTable = null;

    /**
     * @return array|null
     * @throws \Exception
     */
    public static function getExpTable()
    {
        if (null === self::$expTable) {
            $loader = new Loader($_ENV['GAME_DATA_FOLDER_PATH']);
            self::$expTable = $loader->loadByDataType(DataType::ExpTable);
        }

        return self::$expTable;
    }

    /**
     * @param int $exp
     *
     * @return int
     * @throws \Exception
     */
    public static function getLevelByExp(int $exp): int
    {
        $expTable = self::getExpTable();

        foreach ($expTable as $lvl => $neededExp) {
            if ($exp < $neededExp) {
                return (int)($lvl - 1);
            }
        }

        return 1;
    }


    /**
     * @param int $exp
     *
     * @return array
     * @throws \Exception
     */
    public static function getDisplayableExpData(int $exp): array
    {
        $expTable = self::getExpTable();

        foreach ($expTable as $lvl => $neededExp) {
            if ($exp < $neededExp) {
                $start = ($expTable[$lvl - 1] ?? 0);

                return [
                    $exp - $start,
                    $neededExp - $start,
                ];
            }
        }

        return [
            $exp,
            $expTable[2],
        ];
    }

}