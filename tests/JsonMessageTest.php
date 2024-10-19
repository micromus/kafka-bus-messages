<?php

use Micromus\KafkaBusMessages\JsonMessage;

it('convert payload for kafka', function () {
    $message = new JsonMessage(['foo' => 'bar']);

    expect($message->toPayload())
        ->toBe('{"foo":"bar"}');
});
