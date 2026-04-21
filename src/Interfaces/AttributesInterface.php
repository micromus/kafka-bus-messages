<?php

namespace Micromus\KafkaBusMessages\Interfaces;

interface AttributesInterface
{
    public function getKey(): ?string;

    /**
     * @param array<string|int, mixed> $payload
     * @return $this
     */
    public static function from(array $payload): static;

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array;
}
