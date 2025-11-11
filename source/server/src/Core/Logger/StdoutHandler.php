<?php

declare(strict_types=1);

namespace Server\Core\Logger;

use Monolog\Handler\AbstractProcessingHandler;

/**
 * Class StdoutHandler
 *
 * @package Server\Core\Logger
 */
class StdoutHandler extends AbstractProcessingHandler
{

    /**
     * {@inheritDoc}
     */
    protected function write(array $record): void
    {
        echo (string)$record['formatted'];
    }

}