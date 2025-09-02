<?php

namespace Micromus\KafkaBusMessages\Factories;

use Micromus\KafkaBusMessages\DomainEventEnum;
use Micromus\KafkaBusMessages\DomainMessage;

/**
 * @template TMessage of DomainMessage
 *
 * @extends MessageFactory<TMessage>
 */
abstract class DomainMessageFactory extends MessageFactory
{
    /**
     * @param array{
     *     event: string|null,
     *     attributes: array<string, mixed>|null,
     *     dirty: string[]|null
     * } $payload
     * @return TMessage
     */
    protected function make(array $payload): DomainMessage
    {
        $event = DomainEventEnum::tryFrom($payload['event'] ?? 'create')
            ?: DomainEventEnum::Create;

        $attributes = $payload['attributes'] ?? [];

        $dirty = $payload['dirty'] ?? [];

        return $this->makeDomainMessage($event, $attributes, $dirty);
    }

    /**
     * @param DomainEventEnum $event
     * @param array<string, mixed> $attributes
     * @param string[] $dirty
     * @return TMessage
     */
    abstract protected function makeDomainMessage(DomainEventEnum $event, array $attributes, array $dirty): DomainMessage;
}
