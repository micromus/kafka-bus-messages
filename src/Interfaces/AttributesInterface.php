<?php

namespace Micromus\KafkaBusMessages\Interfaces;

interface AttributesInterface
{
    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array;
}
