<?php

namespace Micromus\KafkaBusMessages\Testing;

use Micromus\KafkaBusMessages\DomainEventEnum;
use Micromus\KafkaBusMessages\DomainMessage;
use RdKafka\Message;

/**
 * @template T of DomainMessage
 * @extends MessageTestFactory<T>
 */
abstract class DomainMessageTestFactory extends MessageTestFactory
{
    protected DomainEventEnum $event = DomainEventEnum::Create;

    protected array $dirty = [];

    public function makeArray(array $extra = []): array
    {
        return [
            'event' => $this->event->value,
            'attributes' => parent::makeArray($extra),
            'dirty' => $this->dirty,
        ];
    }

    public function withEvent(DomainEventEnum $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function withDirty(array $dirty): self
    {
        $this->dirty = $dirty;

        return $this;
    }

    protected function makeKafkaMessageFromPayload(array $payload = []): Message
    {
        $message = parent::makeKafkaMessageFromPayload($payload);
        $message->key = $this->getKeyFromAttributes($payload['attributes']);

        return $message;
    }

    abstract protected function getKeyFromAttributes(array $attributes): string;
}
