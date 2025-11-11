<?php

namespace Server\Core\Db\Migrations;

use React\Promise\Promise;

/**
 *
 */
interface MigrationInterface
{
    /**
     * Run the migrations.
     */
    public function run(): Promise;
}