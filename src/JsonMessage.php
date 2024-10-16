<?php

namespace Micromus\KafkaBusDomain;

use Micromus\KafkaBus\Interfaces\Messages\MessageInterface;

class JsonMessage implements MessageInterface
{
    public function __construct(
        protected array $attributes
    ) {
    }


    public function toPayload(): string
    {
        return json_encode($this->attributes);
    }
}
