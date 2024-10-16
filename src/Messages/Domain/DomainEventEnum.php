<?php

namespace Micromus\KafkaBusDomain\Messages\Domain;

enum DomainEventEnum: string
{
    case Create = 'create';
    case Update = 'update';
    case Delete = 'delete';
}
