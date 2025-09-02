<?php

namespace Micromus\KafkaBusMessages;

use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;
use Micromus\KafkaBusMessages\Data\Payload;

class JsonMessage extends Payload implements ProducerMessageInterface
{
    public function toPayload(): string
    {
        return (string) json_encode($this->jsonSerialize());
    }
}
