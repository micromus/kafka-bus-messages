<?php


use Micromus\KafkaBusMessages\Data\Casters\NullableCaster;

test('can cast value', function (mixed $expectedValue) {
    $caster = new NullableCaster(nullable: true);

    expect($caster->cast($expectedValue, 'test'))
        ->toBe($expectedValue);
})->with([
    'value is null' => [null],
    'value is not null' => ['test-value'],
]);

test('can not cast value when caster set not nullable and value is null', function () {
    $caster = new NullableCaster(nullable: false);
    $caster->cast(null, 'test');
})->throws(InvalidArgumentException::class);
