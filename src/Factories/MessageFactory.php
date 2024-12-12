<?php

namespace Micromus\KafkaBusMessages\Factories;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\MessageFactoryInterface;

/**
 * @template T
 */
abstract class MessageFactory implements MessageFactoryInterface
{
    /**
     * @return T
     */
    public function fromKafka(ConsumerMessageInterface $message): mixed
    {
        return $this->make(json_decode($message->payload(), true));
    }

    /**
     * @return T
     */
    abstract protected function make(array $payload): mixed;
}
