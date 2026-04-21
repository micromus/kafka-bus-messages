<?php

namespace Micromus\KafkaBusMessages\Factories;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\MessageFactoryInterface;
use Micromus\KafkaBusMessages\JsonMessage;

/**
 * @template TMessage of JsonMessage
 */
readonly class JsonMessageFactory implements MessageFactoryInterface
{
    /**
     * @param class-string<TMessage> $messageClass
     */
    public function __construct(
        private string $messageClass,
    ) {
    }

    /**
     * @return TMessage
     */
    public function fromKafka(ConsumerMessageInterface $message): mixed
    {
        /** @var array<string|int, mixed> $data */
        $data = json_decode($message->payload(), true);

        return ($this->messageClass)::from($data);
    }
}
