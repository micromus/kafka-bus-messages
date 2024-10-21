<?php

namespace Micromus\KafkaBusMessages\Data\Casters;

use Micromus\KafkaBusMessages\Data\Payload;
use Micromus\KafkaBusMessages\Interfaces\Casters\CasterInterface;
use Webmozart\Assert\Assert;

class PayloadCaster implements CasterInterface
{
    /**
     * @param class-string $payloadClass
     */
    public function __construct(
        protected string $payloadClass,
    ) {
        Assert::classExists($this->payloadClass);
        Assert::isAOf($this->payloadClass, Payload::class);
    }

    public function cast(mixed $value, string $attributeKey): mixed
    {
        if ($value instanceof $this->payloadClass) {
            return $value;
        }

        Assert::isArray($value, "Поле $attributeKey должно быть массивом");

        return new $this->payloadClass($value);
    }

    public function rollback(mixed $value, string $attributeKey): mixed
    {
        return $value instanceof Payload
            ? $value->jsonSerialize()
            : $value;
    }
}
