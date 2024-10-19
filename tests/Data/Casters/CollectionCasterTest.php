<?php

use Micromus\KafkaBusMessages\Data\Casters\CollectionCaster;
use Micromus\KafkaBusMessages\Data\Casters\PayloadCaster;
use Micromus\KafkaBusMessages\Workbench\Data\CategoryPayload;

it('can cast an array', function () {
    $caster = new CollectionCaster(new PayloadCaster(CategoryPayload::class));

    $rawAttributes = [
        [
            'id' => 202410192219,
            'name' => 'Тестовая категория',
        ],
    ];

    /** @var CategoryPayload[] $castedValue */
    $castedValue = $caster->cast($rawAttributes, 'category');

    expect($castedValue)
        ->toHaveCount(1)
        ->and($castedValue[0])
        ->toBeInstanceOf(CategoryPayload::class)
        ->and($castedValue[0]->id)
        ->toBe(202410192219)
        ->and($castedValue[0]->name)
        ->toBe('Тестовая категория');
});
