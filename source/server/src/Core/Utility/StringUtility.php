<?php

declare(strict_types=1);

namespace Server\Core\Utility;

/**
 * Class StringUtility
 *
 * @package Server\Game\Utility
 */
class StringUtility
{

    /**
     * @param string $string
     * @param string $separator
     * @return array
     */
    public static function trimExplode(string $string, string $separator = ','): array
    {
        $parts = explode($separator, $string);
        $parts = array_map(function($item){
            return trim($item);
        }, $parts);
        $parts = array_filter($parts);

        return $parts;
    }
}