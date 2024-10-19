<?php

namespace Micromus\KafkaBusMessages\Factories;

use Micromus\KafkaBusMessages\DomainEventEnum;
use Micromus\KafkaBusMessages\DomainMessage;

/**
 * @template T of DomainMessage
 *
 * @extends MessageFactory<T>
 */
abstract class DomainMessageFactory extends MessageFactory
{
    /**
     * @param array $payload
     * @return DomainMessage
     */
    protected function make(array $payload): DomainMessage
    {
        $event = DomainEventEnum::tryFrom($payload['event'])
            ?: DomainEventEnum::Create;

        $attributes = $payload['attributes'] ?? [];

        $dirty = $payload['dirty'] ?? [];

        return $this->makeDomainMessage($event, $attributes, $dirty);
    }

    /**
     * @param DomainEventEnum $event
     * @param array $attributes
     * @param array $dirty
     * @return DomainMessage
     */
    abstract protected function makeDomainMessage(DomainEventEnum $event, array $attributes, array $dirty): DomainMessage;
}
