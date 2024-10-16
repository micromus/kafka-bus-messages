<?php

namespace Micromus\KafkaBusDomain\Messages\Domain;

class Attributes
{
    public function __construct(
        protected array $attributes = []
    ) {
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}
