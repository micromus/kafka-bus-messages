<?php

namespace Micromus\KafkaBusMessages\Workbench\Data;

use Micromus\KafkaBusMessages\Data\Payload;

/**
 * @property int $id
 * @property string $name
 */
final class CategoryPayload extends Payload
{
    public static function factory(): CategoryPayloadTestFactory
    {
        return CategoryPayloadTestFactory::new();
    }
}
