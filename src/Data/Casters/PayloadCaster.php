<?php

namespace Micromus\KafkaBusMessages\Data\Casters;

use Micromus\KafkaBusMessages\Data\Payload;
use Webmozart\Assert\Assert;

class PayloadCaster extends NullableCaster
{
    /**
     * @param class-string $payloadClass
     * @param bool $nullable
     */
    public function __construct(
        protected string $payloadClass,
        bool $nullable = false
    ) {
        Assert::classExists($this->payloadClass);
        Assert::isAOf($this->payloadClass, Payload::class);

        parent::__construct($nullable);
    }

    public function castNotNull(mixed $value, string $attributeKey): mixed
    {
        if ($value instanceof $this->payloadClass) {
            return $value;
        }

        Assert::isArray($value, "Поле $attributeKey должно быть массивом");

        return new $this->payloadClass($value);
    }

    public function rollbackNotNull(mixed $value, string $attributeKey): string|int|array|null
    {
        return $value instanceof Payload
            ? $value->jsonSerialize()
            : $value;
    }
}
