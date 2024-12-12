<?php

namespace Micromus\KafkaBusMessages\Testing;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\MessageFactoryInterface;
use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;
use RdKafka\Message;

/**
 * @template T of ProducerMessageInterface
 */
abstract class MessageTestFactory extends TestFactory
{
    abstract protected function makeMessageFactory(): MessageFactoryInterface;

    protected function getTopic(): string
    {
        return 'test-topic';
    }

    public function makeKafkaMessage(array $extra = []): Message
    {
        $message = $this->makeKafkaMessageFromPayload($this->makeArray($extra));
        $message->topic_name = $this->getTopic();
        $message->err = RD_KAFKA_RESP_ERR_NO_ERROR;

        return $message;
    }

    public function makeConsumerMessage(array $extra = []): ConsumerMessage
    {
        $message = $this->makeKafkaMessage($extra);

        return new ConsumerMessage($message);
    }

    /**
     * @return T
     */
    public function makeMessage(array $extra = []): ProducerMessageInterface
    {
        return $this->makeMessageFactory()
            ->fromKafka($this->makeConsumerMessage($extra));
    }

    protected function makeKafkaMessageFromPayload(array $payload = []): Message
    {
        $message = new Message();
        $message->payload = json_encode($payload);
        $message->headers = [];
        $message->partition = 0;

        return $message;
    }
}
