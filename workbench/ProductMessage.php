<?php

namespace Micromus\KafkaBusMessages\Workbench;

use Micromus\KafkaBusMessages\Data\Casters\CollectionCaster;
use Micromus\KafkaBusMessages\Data\Casters\PayloadCaster;
use Micromus\KafkaBusMessages\DomainMessage;
use Micromus\KafkaBusMessages\Workbench\Data\AttributePayload;
use Micromus\KafkaBusMessages\Workbench\Data\CategoryPayload;

/**
 * @property int $id
 * @property string $name
 * @property CategoryPayload $category
 * @property AttributePayload[] $attributes
 */
final class ProductMessage extends DomainMessage
{
    public function getKey(): ?string
    {
        return (string) $this->id;
    }

    protected function definitionCasters(): array
    {
        return [
            'category' => new PayloadCaster(CategoryPayload::class),
            'attributes' => new CollectionCaster(new PayloadCaster(AttributePayload::class)),
        ];
    }

    public static function factory(): ProductMessageTestFactory
    {
        return ProductMessageTestFactory::new();
    }
}
