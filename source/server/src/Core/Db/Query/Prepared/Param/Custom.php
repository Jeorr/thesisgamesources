<?php

declare(strict_types=1);

namespace Server\Core\Db\Query\Prepared\Param;

/**
 * Class Custom
 *
 * @package Server\Core\Db\Query\Prepared\Param
 */
class Custom
{
    /**
     * @var array
     */
    protected array $parts;

    /**
     * @var array
     */
    protected array $values;

    /**
     * @param array $parts
     * @param array $values
     */
    public function __construct(array $parts, array $values)
    {
        $this->parts = $parts;
        $this->values = $values;
    }

    /**
     * @return array
     */
    public function getParts(): array
    {
        return $this->parts;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }
}