<?php

namespace Micromus\KafkaBusMessages\Workbench\Data;

use Micromus\KafkaBusMessages\Data\Payload;

/**
 * @property int $id
 * @property string $name
 * @property string $value
 */
final class AttributePayload extends Payload
{
    public static function factory(): AttributePayloadTestFactory
    {
        return AttributePayloadTestFactory::new();
    }
}
