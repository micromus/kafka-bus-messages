<?php

namespace Micromus\KafkaBusMessages\Testing;

use Micromus\KafkaBusMessages\DomainEventEnum;
use Micromus\KafkaBusMessages\DomainMessage;
use Micromus\KafkaBusMessages\Interfaces\AttributesInterface;
use RdKafka\Message;

/**
 * @template TAttribute of AttributesInterface
 */
abstract class DomainMessageTestFactory extends TestFactory
{
    protected DomainEventEnum $event = DomainEventEnum::Create;

    /**
     * @var class-string<TAttribute>
     */
    protected string $attributesClass;

    /**
     * @var string[]
     */
    protected array $dirty = [];

    /**
     * @param array<string|int, mixed> $extra
     * @return array{
     *     event: string,
     *     attributes: array<string|int, mixed>,
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

    /**
     * @param array<string|int, mixed> $extra
     * @return DomainMessage<TAttribute>
     */
    public function message(array $extra = []): DomainMessage
    {
        return new DomainMessage(
            $this->payload($extra),
            $this->event,
            $this->dirty
        );
    }

    /**
     * @param array<string|int, mixed> $extra
     * @return TAttribute
     */
    public function payload(array $extra = []): AttributesInterface
    {
        return ($this->attributesClass)::from($this->makeArray($extra)['attributes']);
    }
}
