<?php

namespace Server\Core\Db\Adapter;

use Psr\Log\LogLevel;
use React\MySQL\ConnectionInterface;
use React\MySQL\Exception;
use React\MySQL\QueryResult;
use React\Promise\Deferred;
use React\Promise\Promise;
use Server\Core\Db\Query\Prepared\Param\Custom as CustomParam;
use Server\Core\Exception\CommonException;

/**
 * Mysql adapter for ReactPHP MySQL
 */
class Mysql implements \Server\Core\Db\Adapter\AdapterInterface
{

    /**
     * @var ConnectionInterface|null
     */
    protected ?ConnectionInterface $connection = null;

    /**
     * @var Deferred|null
     */
    protected ?Deferred $connectedDeferred = null;

    /**
     * @var Promise|null
     */
    protected ?Promise $connectedPromise = null;

    public function __construct(
        protected string $host,
        protected string $database,
        protected string $user,
        protected string $pass,
    ) {
        $this->connectedDeferred = new \React\Promise\Deferred();
        $this->connectedPromise = $this->connectedDeferred->promise();

        $originalFactory = new \React\MySQL\Factory();
        $uri = $this->user . ':' . $this->pass . '@' . $this->host . '/' . $this->database . '?timeout=5';

        $originalFactory->createConnection($uri)
            ->then(
                function (ConnectionInterface $connection) {
                    $this->connection = $connection;
                    $this->connectedDeferred->resolve('Mysql connection is established');
                },
                function (\Throwable $error) {
                    $this->connectedDeferred->reject('Mysql connection could not be established');
                    App()->getLogger()->logException($error);
                }
            )
            ->otherwise(function (\Throwable $error) {
                var_dump($error->getMessage());
                $this->connectedDeferred->reject('Mysql connection could not be established!');
                App()->getLogger()->logException($error);
            })
            ->done();
    }

    /**
     * @return Promise
     */
    public function onConnected(): Promise
    {
        return $this->connectedPromise;
    }

    /**
     * @param string $table
     * @param array $columnsDefinitions
     * @param array $options
     *
     * @return Promise
     */
    public function create(string $table, array $columnsDefinitions, array $options = []): Promise
    {
        $query = 'CREATE TABLE IF NOT EXISTS ' . $table . ' ( ';

        $i = 0;
        foreach ($columnsDefinitions as $columnName => $columnAttr) {
            $query .= ($i > 0 ? ',' : '') . $columnName . ' ' . (implode(' ', $columnAttr));

            $i++;
        }

        $query .= ')';

        // @todo filter options
        if (!empty($options)) {
            $query .= ' ' . (implode(' ', $options));
        }

        $deferred = new \React\Promise\Deferred();
        $promise = $deferred->promise();

        $this->connection->query($query)->then(
            function (QueryResult $command) use ($deferred) {
                $deferred->resolve($command);
            },
            function (Exception $error) use ($deferred, $query) {
                $error = new CommonException($error->getMessage(), $error->getCode(), $error);
                var_dump($query);
                $deferred->reject($error);
            }
        )->otherwise(function (\Throwable $error) use ($deferred, $query) {
            $error = new CommonException($error->getMessage(), $error->getCode(), $error);
            var_dump($query);
            $deferred->reject($error);
        });

        return $promise;
    }

    /**
     * @param string $table
     * @param string $columnName
     * @param array $columnAttr
     * @return Promise
     */
    public function addColumn(string $table, string $columnName, array $columnAttr): Promise
    {
        $query = 'ALTER TABLE ' . $table . ' ADD COLUMN ' . $columnName . ' ' . (implode(' ', $columnAttr));

        $deferred = new \React\Promise\Deferred();
        $promise = $deferred->promise();

        $this->connection
            ->query($query)
            ->then(
                function (QueryResult $command) use ($deferred) {
                    $deferred->resolve($command);
                },
                function (\Throwable $error) use ($deferred) {
                    $error = new CommonException($error->getMessage(), $error->getCode(), $error);
                    if (strpos($error->getMessage(), 'Duplicate column name') !== false) {
                        // ignore duplicate column name error
                        $deferred->resolve();
                        return;
                    }
                    App()->getLogger()->logException($error);
                    $deferred->reject($error);
                }
            )
            ->otherwise(function (\Throwable $error) use ($deferred) {
                $error = new CommonException($error->getMessage(), $error->getCode(), $error);
                App()->getLogger()->logException($error);
                $deferred->reject($error);
            })
            ->done();

        return $promise;
    }


    /**
     * @param string $table
     * @param array $values
     *
     * @return Promise
     */
    public function insert(string $table, array $values): Promise
    {
        $query = 'INSERT INTO ' . $table . ' (' . implode(',', array_keys($values)) . ') 
        VALUES (' . implode(',', array_fill(0, count($values), '?')) . ')';

        $deferred = new \React\Promise\Deferred();
        $promise = $deferred->promise();

        $this->connection
            ->query($query, $values)
            ->then(
                function (QueryResult $command) use ($deferred) {
                    if ($command->insertId !== 0) {
                        $deferred->resolve($command->insertId);
                        echo 'Query OK, ' . $command->affectedRows . ' row(s) affected. Last Insert ID: ' . $command->insertId . PHP_EOL;
                    }
                },
                function (Exception $error) use ($deferred, $query) {
                    $error = new CommonException($error->getMessage(), $error->getCode(), $error);
                    App()->getLogger()->logException($error);
                    $deferred->reject($error);
                }
            )
            ->otherwise(function (\Throwable $error) use ($deferred) {
                $error = new CommonException($error->getMessage(), $error->getCode(), $error);
                App()->getLogger()->logException($error);
                $deferred->reject($error);
            })
            ->done();

        return $promise;
    }

    /**
     * @param string $table
     * @param $fields
     * @param array $where
     * @param int $limit
     * @return Promise
     */
    public function select(string $table, $fields, array $where, int $limit = 0): Promise
    {
        if (is_array($fields)) {
            $fields = implode(',', $fields);
        }

        [$whereExpr, $params] = $this->buildExpression($where);
        $query = 'SELECT ' . $fields . ' FROM ' . $table . ' WHERE ' . $whereExpr;

        if ($limit > 0) {
            $query .= ' LIMIT ' . $limit;
        }

        $deferred = new \React\Promise\Deferred();
        $promise = $deferred->promise();

        $this->connection->query($query, $params)
            ->then(
                function (QueryResult $command) use ($deferred) {
                    $deferred->resolve($command->resultRows ?? []);
                },
                function (Exception $error) use ($deferred, $query) {
                    $error = new CommonException($error->getMessage(), $error->getCode(), $error);
                    App()->getLogger()->logException($error);
                    $deferred->reject($error);
                }
            )
            ->otherwise(function (\Throwable $error) use ($deferred) {
                $error = new CommonException($error->getMessage(), $error->getCode(), $error);
                App()->getLogger()->logException($error);
                $deferred->reject($error);
            })
            ->done();

        return $promise;
    }

    /**
     * @param string $table
     * @param array $where
     * @param int $limit
     * @return Promise
     */
    public function count(string $table, array $where, int $limit = 0): Promise
    {
        [$whereExpr, $params] = $this->buildExpression($where);
        $query = 'SELECT COUNT(*) FROM ' . $table . ' WHERE ' . $whereExpr;

        if ($limit > 0) {
            $query .= ' LIMIT ' . $limit;
        }

        $deferred = new \React\Promise\Deferred();
        $promise = $deferred->promise();

        $this->connection->query($query, $params)
            ->then(
                function (QueryResult $command) use ($deferred) {
                    $deferred->resolve($command->resultRows ? reset($command->resultRows[0]) : 0);
                },
                function (Exception $error) use ($deferred, $query) {
                    $error = new CommonException($error->getMessage(), $error->getCode(), $error);
                    App()->getLogger()->logException($error);
                    $deferred->reject($error);
                }
            )
            ->otherwise(function (\Throwable $error) use ($deferred) {
                $error = new CommonException($error->getMessage(), $error->getCode(), $error);
                App()->getLogger()->logException($error);
                $deferred->reject($error);
            })
            ->done();

        return $promise;
    }

    /**
     * @param string $table
     * @param array $values
     * @param array $where
     *
     * @return Promise
     */
    public function update(string $table, array $values, array $where): Promise
    {
        $params = [];
        $query = 'UPDATE ' . $table . ' SET ';

        $i = 0;
        foreach ($values as $columnName => $columnVal) {
            // @todo test properly
            if ($columnVal instanceof CustomParam) {
                $query .= ($i > 0 ? ',' : '') . $columnName . ' = ' . (implode(' ', $columnVal->getParts()));

                foreach ($columnVal->getValues() as $val) {
                    $params[] = $val;
                }
            } else {
                $query .= ($i > 0 ? ',' : '') . $columnName . ' = ?';
                $params[] = $columnVal;
            }

            $i++;
        }

        if (!empty($where)) {
            [$whereExpr, $whereParams] = $this->buildExpression($where);
            $params = array_merge($params, $whereParams);
            $query .= ' WHERE ' . $whereExpr;
        }

        $deferred = new \React\Promise\Deferred();
        $promise = $deferred->promise();

        $this->connection
            ->query($query, $params)
            ->then(
                function (QueryResult $command) use ($deferred, $query, $params) {
                    $deferred->resolve($command->affectedRows ?? 0);
                },
                function (Exception $error) use ($deferred, $query) {
                    $error = new CommonException($error->getMessage(), $error->getCode(), $error);
                    App()->getLogger()->logException($error);
                    $deferred->reject($error);
                }
            )
            ->otherwise(function (\Throwable $error) use ($deferred) {
                $error = new CommonException($error->getMessage(), $error->getCode(), $error);
                App()->getLogger()->logException($error);
                $deferred->reject($error);
            })
            ->done();

        return $promise;
    }

    /**
     * @param string $table
     * @param $fields
     * @param array $where
     *
     * @return Promise
     */
    public function delete(string $table, array $where): Promise
    {

        [$whereExpr, $params] = $this->buildExpression($where);
        $query = 'DELETE FROM ' . $table . ' WHERE ' . $whereExpr;

        $deferred = new \React\Promise\Deferred();
        $promise = $deferred->promise();

        $this->connection->query($query, $params)
            ->then(
                function (QueryResult $command) use ($deferred) {
                    $deferred->resolve($command->affectedRows ?? 0);
                },
                function (\Throwable $error) use ($deferred, $query) {
                    $error = new CommonException($error->getMessage(), $error->getCode(), $error);
                    App()->getLogger()->logException($error);
                    $deferred->reject($error);
                }
            )
            ->otherwise(function (\Throwable $error) use ($deferred) {
                $error = new CommonException($error->getMessage(), $error->getCode(), $error);
                App()->getLogger()->logException($error);
                $deferred->reject($error);
            })
            ->done();

        return $promise;
    }

    public function buildExpression(array $expr)
    {
        $statement = '';
        $params = [];

        foreach ($expr as $key => $item) {
            $statement .= $this->processExprItem($item, $key, $params);
        }

        return [$statement, $params];
    }

    protected function processExprItem(array $exprItem, $key, &$params)
    {
        if (strtoupper($key) === 'AND' || strtoupper($key) === 'OR') {
            $statement = '(1=1';
            foreach ($exprItem as $innerKey => $innerItem) {
                $statement = $statement . ' ' . strtoupper($key) . ' ' . $this->processExprItem($innerItem, $innerKey, $params);
            }
            $statement .= ')';
        } else {
            $statement = $this->parseExpr($exprItem, $params);
        }

        return $statement;
    }

    protected function parseExpr(array $exprItem, &$params)
    {
        $exprItem = array_values($exprItem);

        if (count($exprItem) < 3) {
            throw new \Exception('Not enough arguments for processExprItem method: ' . count($exprItem) . ' are given and 3 exactly expected');
        }

        $column = $exprItem[0] ?? '';
        $sign = $exprItem[1] ?? '';
        $value = $exprItem[2] ?? '';

        if (empty($column) || empty($sign)) {
            throw new \Exception('First and second arguments must not be empty!');
        }

        if (is_int($value) || is_string($value)) {
            $params[] = $value;
            $value = '?';
        } elseif (is_array($value)) {
            if ('in' != strtolower($sign)) {
                throw new \Exception('Third argument of type "array" is allowed only for sign "in"');
            }
            foreach ($value as $val) {
                $params[] = $val;
            }
            $value = '(' . implode(',', array_fill(0, count($value), '?')) . ')';
        } else {
            throw new \Exception('Wrong third argument variable type! Only int|string|array are allowed');
        }

        return '(' . implode(' ', [$column, $sign, $value]) . ')';
    }
}
