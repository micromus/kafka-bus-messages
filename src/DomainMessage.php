<?php

namespace Micromus\KafkaBusMessages;

use Micromus\KafkaBus\Interfaces\Producers\Messages\HasKey;
use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;
use Micromus\KafkaBusMessages\Interfaces\AttributesInterface;

/**
 * @template T of AttributesInterface
 */
abstract readonly class DomainMessage implements HasKey, ProducerMessageInterface
{
    /**
     * @param T $attributes
     * @param string[] $dirty
     */
    public function __construct(
        public AttributesInterface $attributes,
        public DomainEventEnum $event,
        public array $dirty = []
    ) {
    }

    public function toPayload(): string
    {
        return (string) json_encode([
            'event' => $this->event->value,
            'attributes' => $this->attributes->jsonSerialize(),
            'dirty' => $this->dirty,
        ]);
    }
}
