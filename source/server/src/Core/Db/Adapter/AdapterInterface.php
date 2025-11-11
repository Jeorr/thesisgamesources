<?php

namespace Server\Core\Db\Adapter;

use React\Promise\Promise;

/**
 *
 */
interface AdapterInterface
{
    public function onConnected(): Promise;
    public function create(string $table, array $columnsDefinitions, array $options = []): Promise;
    public function insert(string $table, array $values): Promise;

}