<?php

namespace Micromus\KafkaBusMessages\Workbench;

use Micromus\KafkaBusMessages\Testing\DomainMessageTestFactory;
use Micromus\KafkaBusMessages\Workbench\Data\AttributePayloadTestFactory;
use Micromus\KafkaBusMessages\Workbench\Data\CategoryPayloadTestFactory;

final class ProductDomainMessageTestFactory extends DomainMessageTestFactory
{
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
