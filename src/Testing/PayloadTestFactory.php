<?php

namespace Micromus\KafkaBusMessages\Testing;

use Micromus\KafkaBusMessages\Data\Payload;

/**
 * @template T of Payload
 */
abstract class PayloadTestFactory extends TestFactory
{
    protected string $payloadClass;

    protected function makePayloadFromArray(array $fields): Payload
    {
        return new $this->payloadClass($fields);
    }

    public function makePayload(array $extra = []): Payload
    {
        return $this->makePayloadFromArray($this->makeArray($extra));
    }
}
