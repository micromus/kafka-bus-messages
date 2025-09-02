<?php

namespace Micromus\KafkaBusMessages\Data\Casters;

use Micromus\KafkaBusMessages\Interfaces\Casters\CasterInterface;

class FloatCaster implements CasterInterface
{
    public function cast(mixed $value, string $attributeKey): float
    {
        \assert(is_numeric($value));

        return \floatval($value);
    }

    public function rollback(mixed $value, string $attributeKey): float
    {
        \assert(is_numeric($value));

        return \floatval($value);
    }
}
