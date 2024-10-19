<?php

namespace Micromus\KafkaBusMessages\Data\Casters;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

class DateTimeCaster extends NullableCaster
{
    public function __construct(
        protected string $format = 'Y-m-d\TH:i:s.uP',
        bool $nullable = false
    ) {
        parent::__construct($nullable);
    }

    protected function castNotNull(mixed $value, string $attributeKey): mixed
    {
        return DateTimeImmutable::createFromFormat($this->format, $value);
    }

    protected function rollbackNotNull(mixed $value, string $attributeKey): string|int|array|null
    {
        if ($value instanceof DateTimeInterface) {
            return $value
                ->setTimezone(new DateTimeZone('UTC'))
                ->format($this->format);
        }

        return $value;
    }
}
