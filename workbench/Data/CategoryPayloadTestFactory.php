<?php

namespace Micromus\KafkaBusMessages\Workbench\Data;

use Micromus\KafkaBusMessages\Testing\PayloadTestFactory;

/**
 * @extends PayloadTestFactory<CategoryPayload>
 */
final class CategoryPayloadTestFactory extends PayloadTestFactory
{
    protected string $payloadClass = CategoryPayload::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->numberBetween(),
            'name' => $this->faker->word(),
        ];
    }
}
