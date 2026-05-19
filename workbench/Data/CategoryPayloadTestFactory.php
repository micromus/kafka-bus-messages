<?php

namespace Micromus\KafkaBusMessages\Workbench\Data;

use Micromus\KafkaBusMessages\Testing\PayloadTestFactory;
use Micromus\KafkaBusMessages\Testing\TestFactory;

/**
 * @extends PayloadTestFactory<CategoryPayload>
 */
final class CategoryPayloadTestFactory extends PayloadTestFactory
{
    public function definition(): array
    {
        return [
            'id' => $this->faker->numberBetween(),
            'name' => $this->faker->word(),
        ];
    }
}
