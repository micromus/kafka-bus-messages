<?php

namespace Micromus\KafkaBusMessages\Testing;

use Micromus\KafkaBusMessages\Data\Payload;

/**
 * @template TPayload of Payload
 */
abstract class PayloadTestFactory extends TestFactory
{
    /**
     * @var class-string<TPayload>
     */
    protected string $payloadClass;

    /**
     * @param array<string|int, mixed> $extra
     * @return TPayload
     */
    public function payload(array $extra = []): Payload
    {
        return ($this->payloadClass)::from($this->makeArray($extra));
    }
}
