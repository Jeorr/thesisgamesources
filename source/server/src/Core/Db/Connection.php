<?php

namespace Server\Core\Db;

use React\Promise\Promise;

/**
 *
 */
class Connection
{

    /**
     * @var \Server\Core\Db\Adapter\AdapterInterface
     */
    protected $adapter;

    public function __construct($adapter)
    {
        $this->adapter = $adapter;
    }

    public function onConnected(): Promise
    {
        return $this->adapter->onConnected();
    }

    public function create(string $table, array $columnsDefinitions, array $options = []): Promise
    {
        return $this->adapter->create($table, $columnsDefinitions, $options);
    }

    public function addColumn(string $table, string $columnName, array $columnAttr): Promise
    {
        return $this->adapter->addColumn($table, $columnName, $columnAttr);
    }

    public function insert(string $table, array $values): Promise
    {
        return $this->adapter->insert($table, $values);
    }

    public function select(string $table, $fields, array $where, int $limit = 0): Promise
    {
        return $this->adapter->select($table, $fields, $where, $limit);
    }

    public function count(string $table, array $where, int $limit = 0): Promise
    {
        return $this->adapter->count($table, $where, $limit);
    }

    public function update(string $table, array $values, array $where): Promise
    {
        return $this->adapter->update($table, $values, $where);
    }

    public function delete(string $table, array $where): Promise
    {
        return $this->adapter->delete($table, $where);
    }
}