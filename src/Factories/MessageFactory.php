<?php

namespace Micromus\KafkaBusMessages\Factories;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\MessageFactoryInterface;

/**
 * @template TMessage
 */
abstract class MessageFactory implements MessageFactoryInterface
{
    /**
     * @return TMessage
     */
    public function fromKafka(ConsumerMessageInterface $message): mixed
    {
        /** @var array<string, mixed> $data */
        $data = json_decode($message->payload(), true);

        return $this->make($data);
    }

    /**
     * @param array<string, mixed> $payload
     * @return TMessage
     */
    abstract protected function make(array $payload): mixed;
}
