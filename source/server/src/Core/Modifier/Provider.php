<?php

declare(strict_types=1);

namespace Server\Core\Modifier;

use React\Promise\Promise;
use Server\Core\GameData\Adapter\AdapterInterface;
use Server\Core\GameData\Adapter\InMemory;
use Server\Core\GameData\DataType;
use Server\Core\GameData\Loader;
use Server\Game\Modifiers\ModifierInterface;

/**
 *
 */
class Provider
{
    /**
     * @var array
     */
    protected array $modifiersMap = [];

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $modifiersFolder = rtrim($_ENV['SRC_FOLDER_PATH'] ?? '', '/') . DIRECTORY_SEPARATOR . 'Game' . DIRECTORY_SEPARATOR . 'Modifiers';

        if (!is_dir($modifiersFolder)) {
            throw new \Exception('Wrong $modifiersFolder path!');
        }

        /** @var \DirectoryIterator $fileInfo */
        foreach (new \DirectoryIterator($modifiersFolder) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            $modifierClass = $fileInfo->getBasename('.php');
            $fullModifierClassName = 'Server\\Game\\Modifiers\\' . $modifierClass;

            if ($fullModifierClassName === ModifierInterface::class) {
                // silently skip
                continue;
            }

            if (!class_exists($fullModifierClassName)) {
                throw new \Exception('Php file found in modifiers folder without appropriate class declaration!');
            }

            $modifierObj = new $fullModifierClassName();

            if (!$modifierObj instanceof ModifierInterface){
                throw new \Exception('Modifier class must implement ' . ModifierInterface::class);
            }

            $modifierObjSlots = $modifierObj->getSlots();

            foreach ($modifierObjSlots as $key => $method) {
                $this->modifiersMap[$key][$fullModifierClassName] = [
                    $modifierObj, $method
                ];
            }
        }
    }

    /**
     * @param string $slot
     *
     * @return array
     */
    public function getModifiersForSlot(string $slot) {
        return $this->modifiersMap[$slot] ?? [];
    }
}