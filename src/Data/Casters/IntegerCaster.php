<?php

namespace Micromus\KafkaBusMessages\Data\Casters;

use Micromus\KafkaBusMessages\Interfaces\Casters\CasterInterface;

class IntegerCaster implements CasterInterface
{
    public function cast(mixed $value, string $attributeKey): int
    {
        \assert(is_numeric($value));

        return \intval($value);
    }

    public function rollback(mixed $value, string $attributeKey): int
    {
        \assert(is_numeric($value));

        return \intval($value);
    }
}
