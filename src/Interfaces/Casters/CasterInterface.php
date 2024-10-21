<?php

namespace Micromus\KafkaBusMessages\Interfaces\Casters;

interface CasterInterface
{
    public function cast(mixed $value, string $attributeKey): mixed;

    public function rollback(mixed $value, string $attributeKey): mixed;
}
