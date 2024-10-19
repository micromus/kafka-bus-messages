<?php

namespace Micromus\KafkaBusMessages\Factories;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;
use Micromus\KafkaBus\Interfaces\Messages\MessageFactoryInterface;

/**
 * @template T
 */
abstract class MessageFactory implements MessageFactoryInterface
{
    /**
     * @return T
     */
    public function fromKafka(ConsumerMessage $message): mixed
    {
        return $this->make(json_decode($message->payload, true));
    }

    /**
     * @return T
     */
    abstract protected function make(array $payload): mixed;
}
