# Wundii\Data-Mapper-Symfony-Bundle

[![PHP-Tests](https://github.com/wundii/data-mapper-symfony-bundle/actions/workflows/code_quality.yml/badge.svg)](https://github.com/wundii/data-mapper-symfony-bundle/actions/workflows/code_quality.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%209-brightgreen.svg?style=flat)](https://phpstan.org/)
![VERSION](https://img.shields.io/packagist/v/wundii/data-mapper-symfony-bundle)
[![PHP](https://img.shields.io/packagist/php-v/wundii/data-mapper-symfony-bundle)](https://www.php.net/)
[![Rector](https://img.shields.io/badge/Rector-8.2-blue.svg?style=flat)](https://getrector.com)
[![ECS](https://img.shields.io/badge/ECS-check-blue.svg?style=flat)](https://tomasvotruba.com/blog/zen-config-in-ecs)
[![PHPUnit](https://img.shields.io/badge/PHP--Unit-check-blue.svg?style=flat)](https://phpunit.org)
[![codecov](https://codecov.io/github/wundii/data-mapper-symfony-bundle/branch/main/graph/badge.svg?token=V61OLHU8X3)](https://codecov.io/github/wundii/data-mapper-symfony-bundle)
[![Downloads](https://img.shields.io/packagist/dt/wundii/data-mapper-symfony-bundle.svg?style=flat)](https://packagist.org/packages/wundii/data-mapper-symfony-bundle)

A ***Symfony bundle*** providing seamless integration for the [wundii/data-mapper](https://github.com/wundii/data-mapper).

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
optional formats are marked with an asterisk `*`
- `array`
- `json`
- `neon`*
- `object`
  - `public property`
  - `public getters`
  - `method toArray()`
  - `attribute SourceData('...')`
- `xml`
- `yaml`*

## Installation
Require the bundle and its dependencies with composer:

```bash
composer require wundii/data-mapper-symfony-bundle
```

Include the bundle in your `bundles.php`:

```php
return [
    // ...
    Wundii\DataMapper\SymfonyBundle\DataMapperBundle::class => ['all' => true],
];
```

Create a Symfony configuration file `config/packages/data_mapper.yaml` with the command:

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
