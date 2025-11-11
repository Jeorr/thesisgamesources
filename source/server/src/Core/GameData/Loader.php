<?php

declare(strict_types=1);

namespace Server\Core\GameData;

use Server\Core\Factory\DbFactory;
use Server\Core\Factory\EventDispatcherFactory;
use Server\Events\BaseEvent;
use Server\Events\Subscribers\UserInitGameEventSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 *
 */
class Loader
{

    /**
     * @var string
     */
    protected string $path;

    /**
     * @param  string  $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @param  \Server\Core\GameData\DataType  $dataType
     *
     * @return array
     * @throws \Exception
     */
    public function loadByDataType(DataType $dataType): array
    {
        $filePath = rtrim($this->path, '/') . DIRECTORY_SEPARATOR . strtolower($dataType->name) . '.php';

        if (!file_exists($filePath)) {
            throw new \Exception($filePath . ' could not be found!');
        }

        $data = include $filePath;

        return (array)$data;
    }

    /**
     * @param $domain
     *
     * @return array
     * @throws \Exception
     */
    public function loadTranslationsByDomain($domain = 'general'): array
    {
        $filePath = rtrim($this->path, '/') . DIRECTORY_SEPARATOR . 'translations' . DIRECTORY_SEPARATOR . $domain . '.php';

        if (!file_exists($filePath)) {
            throw new \Exception($filePath . ' could not be found!');
        }

        $data = include $filePath;

        return (array)$data;
    }
}