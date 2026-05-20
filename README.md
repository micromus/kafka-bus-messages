# Kafka Bus Messages for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/micromus/kafka-bus-messages.svg?style=flat-square)](https://packagist.org/packages/micromus/kafka-bus-messages)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/micromus/kafka-bus/run-tests.yml?branch=1.x&label=tests&style=flat-square)](https://github.com/micromus/kafka-bus/actions?query=workflow%3Arun-tests+branch%3A1.x)
[![GitHub Code Style](https://img.shields.io/github/actions/workflow/status/micromus/kafka-bus/php-code-style.yml?branch=1.x&label=code-style&style=flat-square)](https://github.com/micromus/kafka-bus/actions?query=workflow%3Acode-style+branch%3A1.x)
[![GitHub PHPStan](https://img.shields.io/github/actions/workflow/status/micromus/kafka-bus/phpstan.yml?branch=1.x&label=phpstan&style=flat-square)](https://github.com/micromus/kafka-bus/actions?query=workflow%3Aphpstan+branch%3A1.x)

A PHP library for structuring, serializing, and deserializing Kafka messages. It provides typed message payloads with automatic casting, domain event messages, and factory helpers for both production and testing.

## Installation

```bash
composer require micromus/kafka-bus-messages
```

## Core Concepts

- **`Payload`** — a flexible key-value container that supports typed attribute casting (dates, integers, floats, nested payloads, collections).
- **`JsonMessage`** — a `Payload` that serializes itself directly to a JSON Kafka message.
- **`DomainMessage`** — a structured message that wraps an attributes object with a domain event type (`create`, `update`, `delete`) and a list of dirty (changed) fields.
- **Casters** — classes that convert raw values on read (`cast`) and convert them back for serialization (`rollback`).

## Usage

### 1. Defining a Domain Message

Extend `Payload` and define casters in `definitionCasters()` to type your fields automatically.

```php
use Micromus\KafkaBusMessages\Data\Payload;
use Micromus\KafkaBusMessages\Data\Casters\PayloadCaster;
use Micromus\KafkaBusMessages\Data\Casters\CollectionCaster;
use Micromus\KafkaBusMessages\Data\Casters\IntegerCaster;
 
/**
 * @property int            $id
 * @property string         $name
 * @property CategoryPayload    $category
 * @property AttributePayload[] $attributes
 */
class ProductMessage extends \Micromus\KafkaBusMessages\DomainMessage
{
    public function getKey(): ?string
    {
        return (string) $this->id;
    }
 
    protected function definitionCasters(): array
    {
        return [
            'id'         => new IntegerCaster(),
            'category'   => new PayloadCaster(CategoryPayload::class),
            'attributes' => new CollectionCaster(new PayloadCaster(AttributePayload::class)),
        ];
    }
}
 
// Create from a raw array (e.g. decoded JSON)
$product = ProductMessage::from([
    'id'   => '42',
    'name' => 'Laptop',
    'category' => ['id' => 1, 'name' => 'Electronics'],
    'attributes' => [
        ['id' => 10, 'name' => 'Color', 'value' => 'Silver'],
    ],
]);
 
echo $product->id;               // int(42)
echo $product->category->name;   // string("Electronics")
echo $product->attributes[0]->value; // string("Silver")
```
 
### 2. Available Casters

| Caster             | Description                                                   |
|--------------------|---------------------------------------------------------------|
| `IntegerCaster`    | Casts value to `int`                                          |
| `FloatCaster`      | Casts value to `float`                                        |
| `DateTimeCaster`   | Parses/formats `DateTimeInterface` with a configurable format |
| `PayloadCaster`    | Hydrates a nested `Payload` subclass from an array            |
| `CollectionCaster` | Applies another caster to each item in an array               |
| `NullableCaster`   | Wraps any caster to allow `null` values                       |

```php
use Micromus\KafkaBusMessages\Data\Casters\DateTimeCaster;
use Micromus\KafkaBusMessages\Data\Casters\NullableCaster;
use Micromus\KafkaBusMessages\Data\Casters\FloatCaster;
 
protected function definitionCasters(): array
{
    return [
        'published_at' => new DateTimeCaster('Y-m-d\TH:i:s.uP'), // default format
        'deleted_at'   => new NullableCaster(new DateTimeCaster()),
        'price'        => new FloatCaster(),
    ];
}
```
 
### 3. Sending a JSON Message

`JsonMessage` extends `Payload` and implements `ProducerMessageInterface`, so it can be published directly to Kafka.

```php
use Micromus\KafkaBusMessages\JsonMessage;
 
$message = new JsonMessage([
    'order_id' => 123,
    'status'   => 'shipped',
]);
 
// Produces: {"order_id":123,"status":"shipped"}
$message->toPayload();
```
 
---

### 4. Sending a Domain Message

`DomainMessage` wraps an attributes object with a domain event type and a list of changed fields.

```php
use Micromus\KafkaBusMessages\DomainMessage;
use Micromus\KafkaBusMessages\DomainEventEnum;
 
$attributes = [
    'id'   => 42,
    'name' => 'Laptop Pro',
    'category'   => ['id' => 1, 'name' => 'Electronics'],
    'attributes' => [],
];
 
// create / update / delete
$message = new ProductMessage(
    attributes: $attributes,
    event: DomainEventEnum::Update,
    dirty: ['name'],
);
 
// Produces JSON:
// {
//   "event": "update",
//   "attributes": { "id": 42, "name": "Laptop Pro", ... },
//   "dirty": ["name"]
// }
$message->toPayload();
 
// The Kafka partition key comes from getKey() on the attributes object
$message->getKey(); // "42"


// Send to bus
$bus->publish($message);
```
 

### 5. Consuming a Domain Message

Use `DomainMessageFactory` to deserialize an incoming Kafka message into a typed `DomainMessage`.

```php
use Micromus\KafkaBusMessages\Factories\DomainMessageFactory;

class ProductConsumer
{
    #[MessageFactory(new DomainMessageFactory(ProductMessage::class))]
    public function __invoke(ProductMessage $message)
    {
        echo $message->event->value;          // "update"
        echo $message->name;      // "Laptop Pro"
    }
}
```

### 6. Testing Helpers

The library ships with factory base classes to generate realistic test data via [Faker](https://github.com/FakerPHP/Faker).

**Define a test factory:**

```php
use Micromus\KafkaBusMessages\Testing\DomainMessageTestFactory;
 
/**
 * @extends DomainMessageTestFactory<ProductPayload>
 */
final class ProductTestFactory extends DomainMessageTestFactory
{
    protected string $messageClass = ProductMessage::class;
 
    public function definition(): array
    {
        return [
            'id'         => $this->faker->numberBetween(1, 9999),
            'name'       => $this->faker->sentence(),
            'category'   => CategoryPayloadTestFactory::new()->makeArray(),
            'attributes' => [
                AttributePayloadTestFactory::new()->makeArray(),
            ],
        ];
    }
}
```

**Use it in tests:**

```php
// Build a typed DomainMessage with default fake data
$message = ProductMessageTestFactory::new()->message();
 
// Override specific fields
$message = ProductMessageTestFactory::new()
    ->withEvent(DomainEventEnum::Delete)
    ->withDirty(['name', 'category'])
    ->message(['name' => 'Laptop Pro']);
 
// Build a raw RdKafka\Message for lower-level consumer tests
$rdKafkaMessage = ProductMessageTestFactory::new()->make();
 
// Build just the raw array
$array = ProductTestFactory::new()->makeArray();
```

For payload-only factories, extend `PayloadTestFactory`:

```php
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
            'id'   => $this->faker->numberBetween(1, 9999),
            'name' => $this->faker->word(),
        ];
    }
}
 
$category = CategoryPayloadTestFactory::new()->payload();
```


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Kirill Popkov](https://github.com/popkovkirill)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
