<?php

namespace Micromus\KafkaBusMessages\Workbench\Data;

use Micromus\KafkaBusMessages\Testing\TestFactory;

final class AttributePayloadTestFactory extends TestFactory
{
    public function definition(): array
    {
        return [
            'id' => $this->faker->numberBetween(),
            'name' => $this->faker->word,
            'value' => $this->faker->word,
        ];
    }
}
