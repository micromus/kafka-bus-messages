<?php

namespace Micromus\KafkaBusMessages\Workbench;

use Micromus\KafkaBusMessages\DomainMessage;

/**
 * @extends DomainMessage<ProductAttributes>
 */
readonly class ProductDomainMessage extends DomainMessage
{
    public function getKey(): ?string
    {
        return (string) $this->attributes->id;
    }

    public static function factory(): ProductDomainMessageTestFactory
    {
        return ProductDomainMessageTestFactory::new();
    }
}
