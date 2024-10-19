<?php

namespace Micromus\KafkaBusMessages\Testing;

use Faker\Factory;
use Faker\Generator;

abstract class TestFactory
{
    protected array $states = [];

    final public function __construct(
        protected Generator $faker
    ) {
    }

    abstract public function definition(): array;

    public function makeArray(array $extra = []): array
    {
        return array_merge($this->definition(), $this->states, $extra);
    }

    public function withState(array $state): self
    {
        $this->states = array_merge($this->states, $state);

        return $this;
    }

    /**
     * @return static
     */
    public static function new(): self
    {
        return new static(Factory::create('ru_RU'));
    }
}
