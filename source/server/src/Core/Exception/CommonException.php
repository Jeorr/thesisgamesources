<?php

declare(strict_types=1);

namespace Server\Core\Exception;

/**
 * Class CommonException
 *
 * @package Server\Core\Exception
 */
class CommonException extends \Exception
{
    protected bool $isLogged = false;

    public function __construct(
        $message = '',
        $code = 0,
        $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        if ($previous instanceof CommonException) {
            $this->isLogged = $previous->isLogged();
        }
    }

    public function isLogged(): bool {
        return $this->isLogged;
    }

    public function setLogged(): void {
        $this->isLogged = true;
    }
}