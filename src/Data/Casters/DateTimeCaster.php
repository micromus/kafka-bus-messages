<?php

namespace Micromus\KafkaBusMessages\Data\Casters;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Micromus\KafkaBusMessages\Interfaces\Casters\CasterInterface;
use Webmozart\Assert\Assert;

class DateTimeCaster implements CasterInterface
{
    public function __construct(
        protected string $format = 'Y-m-d\TH:i:s.uP',
    ) {
    }

    public function cast(mixed $value, string $attributeKey): DateTimeInterface
    {
        Assert::notNull($value, "Поле $attributeKey не должно быть пустым");

        if ($value instanceof DateTimeInterface) {
            return $value;
        }

        return DateTimeImmutable::createFromFormat($this->format, $value);
    }

    public function rollback(mixed $value, string $attributeKey): mixed
    {
        if ($value instanceof DateTime || $value instanceof DateTimeImmutable) {
            return $value->setTimezone(new DateTimeZone('UTC'))
                ->format($this->format);
        }

        return $value;
    }
}
