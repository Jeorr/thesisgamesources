<?php

declare(strict_types=1);

namespace Server\Core\GameData;

use React\Promise\Promise;
use React\Promise\PromiseInterface;
use Server\Core\GameData\Adapter\AdapterInterface;
use Server\Core\GameData\Adapter\InMemory;

/**
 *
 */
class Provider
{

    protected array $adaptersConf = [
        [
            'adapter' => InMemory::class,
            'settings' => [],
            'dataTypes' => [
                DataType::Units,
                DataType::NPCs,
                DataType::Skills,
                DataType::Buffs,
                DataType::Locations,
                DataType::Territories,
                DataType::Elixirs,
                DataType::Structures,
                DataType::Items,
                DataType::Talents
            ],
        ],
    ];

    protected array $dataTypeToAdapterMap = [];

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $loader = new Loader($_ENV['GAME_DATA_FOLDER_PATH']);

        foreach ($this->adaptersConf as $conf) {
            $adapterClass = $conf['adapter'] ?? null;

            if (!class_exists($adapterClass)) {
                throw new \Exception('GameData adapter class ' . $adapterClass . ' could not be found!');
            }

            $interfaces = class_implements($adapterClass);

            if (!isset($interfaces[AdapterInterface::class])) {
                throw new \Exception('Adapter class must implement ' . AdapterInterface::class);
            }

            $adapterSettings = $conf['settings'];
            $adapter = new $adapterClass($loader, $adapterSettings);


            $dataTypes = $conf['dataTypes'];

            if (empty($dataTypes)) {
                continue;
            }

            foreach ($dataTypes as $dataType) {
                if (!$dataType instanceof DataType) {
                    throw new \Exception('List of dataTypes contains wrong enum class, only ' . DataType::class . ' is supported!');
                }

                if (isset($this->dataTypeToAdapterMap[$dataType->name])) {
                    throw new \Exception('Adapter for the given dataType ' . $dataType->name . ' is already registered!');
                }
                $this->dataTypeToAdapterMap[$dataType->name] = $adapter->initForDataType($dataType);
            }
        }
    }

    /**
     * @param  \Server\Core\GameData\DataType  $dataType
     *
     * @return \Server\Core\GameData\Adapter\AdapterInterface
     * @throws \Exception
     */
    protected function getAdapterForDataType(DataType $dataType): AdapterInterface
    {
        if (!isset($this->dataTypeToAdapterMap[$dataType->name])) {
            throw new \Exception('Adapter is not registered for the given data type: ' . $dataType->name);
        }

        return $this->dataTypeToAdapterMap[$dataType->name];
    }

    /**
     * @param  \Server\Core\GameData\DataType  $dataType
     *
     * @return array
     * @throws \Exception
     */
    public function getKeys(DataType $dataType): array
    {
        return $this->getAdapterForDataType($dataType)->getKeys($dataType);
    }

    /**
     * @param  \Server\Core\GameData\DataType  $dataType
     * @param  string  $key
     *
     * @return \React\Promise\Promise
     * @throws \Exception
     */
    public function getData(DataType $dataType, string $key): PromiseInterface
    {
        $deferred = new \React\Promise\Deferred();

        $this->getAdapterForDataType($dataType)
             ->getData($dataType, $key)
             ->then(
                 function ($data) use ($deferred, $key) {
                     if (is_array($data) && !isset($data['code'])) {
                         $data['code'] = $key;
                     }
                     $deferred->resolve($data);
                 },
                 function (\Exception $error) use ($deferred) {
                     $deferred->reject($error);
                 },
             )
             ->done();

        return $deferred->promise();
    }


    /**
     * @param DataType $dataType
     * @param array $filters
     * @return Promise
     * @throws \Exception
     */
    public function getFiltered(DataType $dataType, array $filters): PromiseInterface
    {
        $deferred = new \React\Promise\Deferred();

        $this->getAdapterForDataType($dataType)
            ->getFiltered($dataType, $filters)
            ->then(
                function ($filtered) use ($deferred) {
                    foreach ($filtered as $key => $data) {
                        if (is_array($data) && !isset($data['code'])) {
                            $filtered[$key]['code'] = $key;
                        }
                    }

                    $deferred->resolve($filtered);
                },
                function (\Exception $error) use ($deferred) {
                    $deferred->reject($error);
                },
            )
            ->done();

        return $deferred->promise();
    }
}