<?php

namespace Micromus\KafkaBusMessages\Workbench;

use Micromus\KafkaBus\Interfaces\Messages\MessageFactoryInterface;
use Micromus\KafkaBusMessages\Testing\DomainMessageTestFactory;
use Micromus\KafkaBusMessages\Workbench\Data\AttributePayloadTestFactory;
use Micromus\KafkaBusMessages\Workbench\Data\CategoryPayloadTestFactory;

final class ProductDomainMessageTestFactory extends DomainMessageTestFactory
{
    protected function getKeyFromAttributes(array $attributes): string
    {
        return $attributes['id'];
    }

    protected function makeMessageFactory(): MessageFactoryInterface
    {
        return new ProductDomainMessageFactory();
    }

    public function definition(): array
    {
        return [
            'id' => $this->faker->numberBetween(),
            'name' => $this->faker->sentence(),
            'category' => CategoryPayloadTestFactory::new()->makeArray(),
            'attributes' => [
                AttributePayloadTestFactory::new()
                    ->makeArray(),
            ],
        ];
    }
}
