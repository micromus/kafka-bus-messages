<?php

namespace Micromus\KafkaBusMessages;

enum DomainEventEnum: string
{
    case Create = 'create';
    case Update = 'update';
    case Delete = 'delete';
}
