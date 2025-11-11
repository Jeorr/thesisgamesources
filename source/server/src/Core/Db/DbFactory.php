<?php

declare(strict_types=1);

namespace Server\Core\Db;

use Server\Core\Db\Adapter\Mysql;

/**
 *
 */
class DbFactory
{

    /**
     * @return \Server\Core\Db\Connection
     */
    public function createMysqlConnection()
    {
        $host = $_ENV['DB_HOST'];
        $database = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASSWORD'];

        if (!$host || !$database || !$user || !$pass) {
            throw new \Exception('Database credentials are not set');
        }   

        return new Connection(new Mysql($host, $database, $user, $pass));
    }

}