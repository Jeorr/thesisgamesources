<?php

namespace Server\Core\GameData\Adapter;

use React\Promise\Promise;
use Server\Core\GameData\DataType;

/**
 *
 */
interface AdapterInterface
{

    public function getKeys(DataType $dataType): array;

    public function getData(DataType $dataType, string $key): Promise;

    public function getFiltered(DataType $dataType, array $filters): Promise;
}