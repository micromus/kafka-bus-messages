<?php

namespace Micromus\KafkaBusMessages\Data;

use ArrayAccess;
use JsonSerializable;
use Micromus\KafkaBusMessages\Interfaces\Casters\CasterInterface;

/**
 *
 */
class Payload implements JsonSerializable, ArrayAccess
{
    private array $attributes = [];

    /**
     * @var array<string, CasterInterface>
     */
    private array $casters;

    public function __construct($attributes = [])
    {
        $this->casters = $this->definitionCasters();

        foreach ($attributes as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }

    /**
     * @return array<string, CasterInterface>
     */
    protected function definitionCasters(): array
    {
        return [];
    }

    public function jsonSerialize(): array
    {
        $castedAttributes = array_map(function (mixed $value, string $attributeKey) {
            return array_key_exists($attributeKey, $this->casters)
                ? $this->casters[$attributeKey]->rollback($value, $attributeKey)
                : $value;
        }, $this->attributes, array_keys($this->attributes));

        return array_combine(array_keys($this->attributes), $castedAttributes);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->attributes[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->attributes[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->attributes[$offset] = array_key_exists($offset, $this->casters)
            ? $this->casters[$offset]->cast($value, $offset)
            : $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->attributes[$offset]);
    }

    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }
}
