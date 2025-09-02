<?php

namespace Micromus\KafkaBusMessages\Testing;

use Micromus\KafkaBusMessages\DomainEventEnum;
use Micromus\KafkaBusMessages\DomainMessage;
use RdKafka\Message;

abstract class DomainMessageTestFactory extends TestFactory
{
    protected DomainEventEnum $event = DomainEventEnum::Create;

    /**
     * @var string[]
     */
    protected array $dirty = [];

    /**
     * @param array<string, mixed> $extra
     * @return array{
     *     event: string,
     *     attributes: array<string, mixed>,
     *     dirty: string[]
     * }
     */
    public function makeArray(array $extra = []): array
    {
        return [
            'event' => $this->event->value,
            'attributes' => parent::makeArray($extra),
            'dirty' => $this->dirty,
        ];
    }

    /**
     * @param DomainEventEnum $event
     * @return $this
     */
    public function withEvent(DomainEventEnum $event): static
    {
        return $this->immutableSet('event', $event);
    }

    /**
     * @param string[] $dirty
     * @return $this
     */
    public function withDirty(array $dirty): static
    {
        return $this->immutableSet('dirty', $dirty);
    }
}
