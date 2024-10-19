<?php

namespace Micromus\KafkaBusMessages\Interfaces;

interface AttributesInterface
{
    public function jsonSerialize(): array;
}
