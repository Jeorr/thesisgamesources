<?php

declare(strict_types=1);

namespace Server\Game\UseFunctions;

use React\Promise\PromiseInterface;

/**
 *
 */
interface UseFunctionInterface
{
    public function use(...$args): PromiseInterface;
}