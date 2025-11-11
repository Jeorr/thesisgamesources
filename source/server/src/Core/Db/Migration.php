<?php

namespace Server\Core\Db;

use React\Promise\Promise;
use Server\App;
use Server\Core\Db\Migrations\MigrationInterface;
use Server\Game\Managers\UserManager;

/**
 *
 */
class Migration
{
    /**
     * Run the migrations.
     */
    public function runMigrations(): Promise
    {
        $migrationsList = [];
        $iterator = new \FilesystemIterator(__DIR__ . '/Migrations');

        /** @var \SplFileInfo $entry */
        foreach ($iterator as $entry) {
            $className = $entry->getBasename('.php');
            $fullClassName = '\\Server\\Core\\Db\\Migrations\\' . $className;

            $reflection = new \ReflectionClass($fullClassName);

            if ($reflection->isInterface()) {
                continue;
            }

            $migration = new $fullClassName();

            if (!$migration instanceof MigrationInterface) {
                throw new \Exception('Migration class ' . $fullClassName . ' must implement '. MigrationInterface::class);
            }

            $migrationsList[] = $migration->run();
        }

        return \React\Promise\all($migrationsList);
    }
}