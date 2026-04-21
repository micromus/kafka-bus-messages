<?php

namespace Micromus\KafkaBusMessages\Factories;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\MessageFactoryInterface;
use Micromus\KafkaBusMessages\DomainEventEnum;
use Micromus\KafkaBusMessages\DomainMessage;
use Micromus\KafkaBusMessages\Interfaces\AttributesInterface;

/**
 * @template TAttributes of AttributesInterface
 */
readonly class DomainMessageFactory implements MessageFactoryInterface
{
    /**
     * @param class-string<TAttributes> $attributesClass
     */
    public function __construct(
        private string $attributesClass,
    ) {
    }

    /**
     * @param ConsumerMessageInterface $message
     * @return DomainMessage<TAttributes>
     */
    public function fromKafka(ConsumerMessageInterface $message): mixed
    {
        /** @var array{
         *      event: string|null,
         *      attributes: array<string|int, mixed>|null,
         *      dirty: string[]|null
         * } $data */
        $data = json_decode($message->payload(), true);

        $event = DomainEventEnum::tryFrom($data['event'] ?? 'create')
            ?: DomainEventEnum::Create;

        $attributes = $data['attributes'] ?? [];

        $dirty = $data['dirty'] ?? [];

        return new DomainMessage(($this->attributesClass)::from($attributes), $event, $dirty);
    }
}
