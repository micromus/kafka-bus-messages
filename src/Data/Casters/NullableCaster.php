<?php

namespace Micromus\KafkaBusMessages\Data\Casters;

use Micromus\KafkaBusMessages\Interfaces\Casters\CasterInterface;
use Webmozart\Assert\Assert;

class NullableCaster implements CasterInterface
{
    public function __construct(
        protected bool $nullable = false
    ) {
    }

    public function cast(mixed $value, string $attributeKey): mixed
    {
        if (!$this->nullable) {
            Assert::notNull($value, "Поле $attributeKey не должно быть пустым");
        }

        if (is_null($value)) {
            return null;
        }

        return $this->castNotNull($value, $attributeKey);
    }

    protected function castNotNull(mixed $value, string $attributeKey): mixed
    {
        return $value;
    }

    public function rollback(mixed $value, string $attributeKey): string|int|array|null
    {
        Assert::notNull($value, "Поле $attributeKey не должно быть пустым");

        return $this->rollbackNotNull($value, $attributeKey);
    }

    protected function rollbackNotNull(mixed $value, string $attributeKey): string|int|array|null
    {
        return $value;
    }
}
