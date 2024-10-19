<?php

namespace Micromus\KafkaBusMessages;

use Micromus\KafkaBus\Interfaces\Messages\HasKey;
use Micromus\KafkaBus\Interfaces\Messages\MessageInterface;
use Micromus\KafkaBusMessages\Interfaces\AttributesInterface;

/**
 * @template T of AttributesInterface
 */
abstract readonly class DomainMessage implements HasKey, MessageInterface
{
    /**
     * @param T $attributes
     */
    public function __construct(
        public AttributesInterface $attributes,
        public DomainEventEnum $event,
        public array $dirty = []
    ) {
    }

    public function toPayload(): string
    {
        return json_encode([
            'event' => $this->event->value,
            'attributes' => $this->attributes->jsonSerialize(),
            'dirty' => $this->dirty,
        ]);
    }
}