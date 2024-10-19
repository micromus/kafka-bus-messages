<?php

namespace Micromus\KafkaBusMessages;

use Micromus\KafkaBus\Interfaces\Messages\MessageInterface;
use Micromus\KafkaBusMessages\Data\Payload;

class JsonMessage extends Payload implements MessageInterface
{
    public function toPayload(): string
    {
        return json_encode($this->jsonSerialize());
    }
}
