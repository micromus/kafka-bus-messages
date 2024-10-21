<?php


use Micromus\KafkaBusMessages\Data\Casters\IntegerCaster;
use Micromus\KafkaBusMessages\Data\Casters\NullableCaster;

test('can cast value', function (mixed $expectedValue) {
    $caster = new NullableCaster(new IntegerCaster());

    expect($caster->cast($expectedValue, 'test'))
        ->toBe($expectedValue);
})->with([
    'value is null' => [null],
    'value is not null' => [202410212239],
]);
