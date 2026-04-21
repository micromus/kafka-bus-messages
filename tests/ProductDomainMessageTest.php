<?php

namespace Micromus\KafkaBusMessages\Tests;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;
use Micromus\KafkaBusMessages\DomainEventEnum;
use Micromus\KafkaBusMessages\Factories\DomainMessageFactory;
use Micromus\KafkaBusMessages\Workbench\ProductPayload;
use RdKafka\Message;
use Testo\Assert;
use Testo\Test;

class ProductDomainMessageTest
{
    #[Test]
    public function create_domain_message_from_kafka(): void
    {
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
        $message->payload = (string)json_encode($raw);

        $productDomainMessage = (new DomainMessageFactory(ProductPayload::class))
            ->fromKafka(new ConsumerMessage($message));

        Assert::equals($productDomainMessage->event, DomainEventEnum::Create);
        Assert::equals($productDomainMessage->dirty, ['test']);

        Assert::equals($productDomainMessage->attributes->id, 202410192253);
        Assert::equals($productDomainMessage->attributes->name, 'Тестовый товар');

        Assert::equals($productDomainMessage->attributes->category->id, 202410192254);
        Assert::equals($productDomainMessage->attributes->category->name, 'Тестовая категория');

        Assert::array($productDomainMessage->attributes->attributes)->hasCount(1);

        $attribute = $productDomainMessage->attributes->attributes[0];

        Assert::equals($attribute->id, 202410192246);
        Assert::equals($attribute->name, 'Цвет');
        Assert::equals($attribute->value, 'Белый');
    }
}
