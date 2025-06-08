# Wundii\Data-Mapper-Symfony-Bundle

A Symfony bundle providing seamless integration for the [Wundii Data Mapper](https://github.com/wundii/data-mapper).

## Features
- Mapping source data into objects
- Mapping source data with a list of elements into a list of objects
- Initialize object via constructor, properties or methods
- Map nested objects, arrays of objects
- Class mapping for interfaces or other classes
- Custom root element for starting with the source data
- Auto-casting for `float` types (eu to us decimal separator)

## Supported Types
- `null`
- `bool`|`?bool`
- `int`|`?int`
- `float`|`?float`
- `string`|`?string`
- `array`
    - `int[]`
    - `float[]`
    - `string[]`
    - `object[]`
- `object`|`?object`
- `enum`|`?enum`

## Supported Formats
- `array`
- `json`
- `xml`

## Installation
Require the bundle and its dependencies with composer:

```bash
composer require wundii/data-mapper-symfony-bundle
```

Include the bundle in your `bundles.php`:

```php
return [
    // ...
    Wundii\DataMapperBundle\WundiiDataMapperBundle::class => ['all' => true],
];
```

Create a symfony configuration file `config/packages/data_mapper.yaml`:

```bash
bin/console data-mapper:default-config
```

## Configuration File
The following setting options are available

```yaml
data_mapper:
    data_config:
        approach: 'CONSTRUCTOR|PROPERTY|SETTER' # ApproachEnum::SETTER
        accessible: 'PRIVATE|PUBLIC' # AccessibleEnum::PUBLIC
        class_map: 
          InterfaceOrClassName: 'ClassName', # Class mapping for interfaces or other classes
          ...: ...
```