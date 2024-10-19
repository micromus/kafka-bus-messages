<?php

use Micromus\KafkaBusMessages\Data\Payload;

it('get attribute', function () {
    $payload = new Payload(['test' => 'foo-bar']);

    expect($payload->test)
        ->toBe('foo-bar');
});

it('get attribute as array', function () {
    $payload = new Payload(['test' => 'foo-bar']);

    expect($payload['test'])
        ->toBe('foo-bar');
});

it('get all attributes', function () {
    $payload = new Payload(['test' => 'foo-bar']);

    expect($payload->jsonSerialize())
        ->toBe(['test' => 'foo-bar']);
});
