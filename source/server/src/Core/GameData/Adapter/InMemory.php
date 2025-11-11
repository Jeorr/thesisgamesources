<?php

declare(strict_types=1);

namespace Server\Core\GameData\Adapter;

use React\Promise\Promise;
use Server\Core\GameData\DataType;
use Server\Core\GameData\Loader;

/**
 *
 */
class InMemory implements AdapterInterface
{

    /**
     * @var \Server\Core\GameData\Loader
     */
    protected Loader $loader;

    /**
     * @var array
     */
    protected array $memoryArray = [];

    public function __construct(Loader $loader, array $settings = [])
    {
        $this->loader = $loader;
    }

    /**
     * @param  \Server\Core\GameData\DataType  $dataType
     *
     * @return $this
     * @throws \Exception
     */
    public function initForDataType(DataType $dataType): self
    {
        $data = $this->loader->loadByDataType($dataType);
        $this->memoryArray[$dataType->name] = $data;

        return $this;
    }

    /**
     * @param  \Server\Core\GameData\DataType  $dataType
     *
     * @return array
     */
    public function getKeys(DataType $dataType): array
    {
        return array_keys($this->memoryArray[$dataType->name] ?? []);
    }

    /**
     * @param  \Server\Core\GameData\DataType  $dataType
     * @param  string  $key
     *
     * @return \React\Promise\Promise
     */
    public function getData(DataType $dataType, string $key): Promise
    {
        $deferred = new \React\Promise\Deferred();
        $promise = $deferred->promise();

        $deferred->resolve($this->memoryArray[$dataType->name][$key] ?? null);

        return $promise;
    }

    /**
     * @param DataType $dataType
     * @param array $filters
     * @return Promise
     * @throws \Exception
     */
    public function getFiltered(DataType $dataType, array $filters): Promise
    {
        $deferred = new \React\Promise\Deferred();
        $promise = $deferred->promise();
        $filteredItems = [];

        foreach ($this->memoryArray[$dataType->name] as $key => $item) {
            $matchedFilters = 0;

            foreach ($filters as $attribute => $value) {
                if (!isset($item[$attribute])) {
                    if ($attribute === 'code') {
                        $item['code'] = $key;
                    } else {
                        throw new \Exception('Given attribute name ' . $attribute . ' is not supported by the record type ' . $dataType->name);
                    }
                }

                if (is_array($value)) {
                    foreach ($value as $val) {
                        if ($val === $item[$attribute]) {
                            $matchedFilters++;
                            break;
                        }
                    }
                } elseif ($value === $item[$attribute]) {
                    $matchedFilters++;
                }
            }

            if (count($filters) === $matchedFilters) {
                $filteredItems[$key] = $item;
            }
        }

        $deferred->resolve($filteredItems);

        return $promise;
    }
}