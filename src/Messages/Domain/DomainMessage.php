<?php

namespace Micromus\KafkaBusDomain\Messages\Domain;

use Micromus\KafkaBus\Interfaces\Messages\HasKey;
use Micromus\KafkaBus\Interfaces\Messages\MessageInterface;

/**
 * @template T of Attributes
 */
abstract readonly class DomainMessage implements HasKey, MessageInterface
{
    /**
     * @param Attributes $attributes
     */
    public function __construct(
        public Attributes $attributes,
        public DomainEventEnum $event,
        public array $dirty = []
    ) {
    }

    public function toPayload(): string
    {
        return json_encode([
            'event' => $this->event->value,
            'attributes' => $this->attributes->toArray(),
            'dirty' => $this->dirty,
        ]);
    }
}
