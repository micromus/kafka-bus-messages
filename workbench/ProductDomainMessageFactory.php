<?php

namespace Micromus\KafkaBusMessages\Workbench;

use Micromus\KafkaBusMessages\DomainEventEnum;
use Micromus\KafkaBusMessages\DomainMessage;
use Micromus\KafkaBusMessages\Factories\DomainMessageFactory;

/**
 * @extends DomainMessageFactory<ProductDomainMessage>
 */
final class ProductDomainMessageFactory extends DomainMessageFactory
{
    protected function makeDomainMessage(DomainEventEnum $event, array $attributes, array $dirty): DomainMessage
    {
        return new ProductDomainMessage(new ProductAttributes($attributes), $event, $dirty);
    }
}
