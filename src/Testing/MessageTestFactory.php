<?php

namespace Micromus\KafkaBusMessages\Testing;

use Faker\Factory;
use Faker\Generator;
use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;
use Micromus\KafkaBus\Consumers\Messages\ConsumerMeta;
use Micromus\KafkaBus\Interfaces\Messages\MessageFactoryInterface;
use Micromus\KafkaBus\Interfaces\Messages\MessageInterface;
use RdKafka\Message;

/**
 * @template T of MessageInterface
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

        return new ConsumerMessage(
            payload: $message->payload,
            headers: $message->headers,
            meta: new ConsumerMeta($message)
        );
    }

    /**
     * @return T
     */
    public function makeMessage(array $extra = []): MessageInterface
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
