<?php

declare(strict_types=1);

namespace Server\Game\Models;

use Server\Core\Utility\StringUtility;

/**
 *
 */
class BaseModel
{
    /**
     * @var array
     */
    protected array $meta = [];

    /**
     * @return array
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * @param array $meta
     */
    public function setMeta(array $meta): void
    {
        $this->meta = $meta;
    }

    public function __construct(array $data = [])
    {
        $this->fromArray($data);
    }

    public function fromArray(array $data)
    {
        foreach ($data as $property => $propertyValue) {
            $property = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $property))));

            if (!property_exists($this, $property)) {
                continue;
                //throw new \Exception('Property ' . $property . ' does not exists for the object ' . static::class);
            }

            $rp = new \ReflectionProperty(static::class, $property);
            $propertyType = $rp->getType()->getName();

            if ($propertyType === 'array' && is_string($propertyValue)) {
                try {
                    $propertyValue = json_decode($propertyValue, true, 512, JSON_THROW_ON_ERROR );
                } catch (\JsonException $e) {
                    $propertyValue = StringUtility::trimExplode($propertyValue, ',');
                }
            }
            
            settype($propertyValue, $propertyType);

            $this->$property = $propertyValue;
        }

        return $this;
    }

    public function isPropertyInitialized($property)
    {
        $rp = new \ReflectionProperty(static::class, $property);

        return $rp->isInitialized($this);
    }
}