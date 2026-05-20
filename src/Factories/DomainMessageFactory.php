<?php

namespace Micromus\KafkaBusMessages\Factories;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\MessageFactoryInterface;
use Micromus\KafkaBusMessages\DomainEventEnum;
use Micromus\KafkaBusMessages\DomainMessage;

/**
 * @template TMessage of DomainMessage
 */
readonly class DomainMessageFactory implements MessageFactoryInterface
{
    /**
     * @param class-string<TMessage> $messageClass
     */
    public function __construct(
        private string $messageClass,
    ) {
    }

    /**
     * @param ConsumerMessageInterface $message
     * @return TMessage
     */
    public function fromKafka(ConsumerMessageInterface $message): mixed
    {
        /** @var array{
         *      event: string|null,
         *      attributes: array<string, mixed>|null,
         *      dirty: list<string>|null
         * } $data */
        $data = json_decode($message->payload(), true);

        $event = DomainEventEnum::tryFrom($data['event'] ?? 'create')
            ?: DomainEventEnum::Create;

        $attributes = $data['attributes'] ?? [];

        $dirty = $data['dirty'] ?? [];

        return ($this->messageClass)::create($attributes, $event, $dirty);
    }
}
