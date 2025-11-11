<?php

namespace Server\Core\Logger;

use Monolog\Handler\StreamHandler;
use Psr\Log\LogLevel;
use Server\Core\Exception\CommonException;

/**
 * Class Logger
 *
 * @package Server\Core\Logger
 */
class Logger
{

    protected array $loggers = [];

    public function __construct(array $config = [])
    {
        $this->loggers['file'] = new \Monolog\Logger('file', [new StreamHandler(LOG_FILE_PATH, LogLevel::WARNING)]);
        $this->loggers['stdout'] = new \Monolog\Logger('stdout', [new StdoutHandler(LogLevel::DEBUG)]);
    }

    public function simpleLog(string $message, string $logLevel = LogLevel::DEBUG)
    {
        /** @var \Monolog\Logger $logger */
        foreach ($this->loggers as $logger) {
            $logger->log($logLevel, $message);
        }
    }

    public function log(string $message, array $context = [], string $logLevel = LogLevel::DEBUG)
    {
        /** @var \Monolog\Logger $logger */
        foreach ($this->loggers as $logger) {
            $logger->log($logLevel, $message, $context);
        }
    }

    /**
     * @param \Throwable $error
     * @param string $logLevel
     * @return void
     */
    public function logException(\Throwable $error, string $logLevel = LogLevel::ERROR)
    {
        if ($error instanceof CommonException) {
            if ($error->isLogged()) {
                return;
            }

            $error->setLogged();
            $this->log($error->getMessage(), $error->getTrace(), $logLevel);
            return;
        }

        $this->log($error->getMessage(), $error->getTrace(), $logLevel);
    }

}