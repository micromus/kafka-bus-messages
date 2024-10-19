<?php

use Micromus\KafkaBusMessages\Data\Casters\DateTimeCaster;

it('cast date time', function () {
    $caster = new DateTimeCaster();

    /** @var DateTime $datetime */
    $datetime = $caster->cast('2020-01-01T06:00:00.000000+03:00', 'updated_at');
    $rollback = $caster->rollback($datetime, 'updated_at');

    expect($datetime->getTimezone()->getName())
        ->toBe('+03:00')
        ->and($rollback)
        ->toBe('2020-01-01T03:00:00.000000+00:00');
});
