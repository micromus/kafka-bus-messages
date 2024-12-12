<?php

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;
use Micromus\KafkaBus\Consumers\Messages\ConsumerMeta;
use Micromus\KafkaBusMessages\DomainEventEnum;
use Micromus\KafkaBusMessages\Workbench\ProductDomainMessageFactory;
use RdKafka\Message;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;

it('create domain message from kafka', function () {
    $raw = [
        'event' => DomainEventEnum::Create->value,
        'attributes' => [
            'id' => 202410192253,
            'name' => 'Тестовый товар',
            'category' => [
                'id' => 202410192254,
                'name' => 'Тестовая категория',
            ],
            'attributes' => [
                [
                    'id' => 202410192246,
                    'name' => 'Цвет',
                    'value' => 'Белый',
                ],
            ],
        ],
        'dirty' => ['test'],
    ];

    $message = new Message();
    $message->payload = json_encode($raw);

    $productDomainMessage = (new ProductDomainMessageFactory())
        ->fromKafka(new ConsumerMessage($message));

    assertEquals(DomainEventEnum::Create, $productDomainMessage->event);
    assertEquals(['test'], $productDomainMessage->dirty);

    assertEquals(202410192253, $productDomainMessage->attributes->id);
    assertEquals('Тестовый товар', $productDomainMessage->attributes->name);

    assertEquals(202410192254, $productDomainMessage->attributes->category->id);
    assertEquals('Тестовая категория', $productDomainMessage->attributes->category->name);

    assertCount(1, $productDomainMessage->attributes->attributes);
    assertEquals(202410192246, $productDomainMessage->attributes->attributes[0]->id);
    assertEquals('Цвет', $productDomainMessage->attributes->attributes[0]->name);
    assertEquals('Белый', $productDomainMessage->attributes->attributes[0]->value);
});
