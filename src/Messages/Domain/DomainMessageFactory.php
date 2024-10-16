<?php

namespace Micromus\KafkaBusDomain\Messages\Domain;

use Micromus\KafkaBusDomain\Messages\MessageFactory;

/**
 * @template T of DomainMessage
 *
 * @extends MessageFactory<T>
 */
abstract class DomainMessageFactory extends MessageFactory
{
    /**
     * @return T
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
     * @return T
     */
    abstract protected function makeDomainMessage(DomainEventEnum $event, array $attributes, array $dirty): DomainMessage;
}
