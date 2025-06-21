<p style="text-align:center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/wundii/data-mapper/refs/heads/main/assets/data-mapper-dark.png">
    <source media="(prefers-color-scheme: light)" srcset="https://raw.githubusercontent.com/wundii/data-mapper/refs/heads/main/assets/data-mapper-light.png">
    <img src="https://raw.githubusercontent.com/wundii/data-mapper/refs/heads/main/assets/data-mapper-light.png" alt="wundii/data-mapper-symfony-bundle" style="width: 100%; max-width: 600px; height: auto;">
  </picture>
</p>

[![PHP-Tests](https://img.shields.io/github/actions/workflow/status/wundii/data-mapper-symfony-bundle/code_quality.yml?branch=main&style=for-the-badge)](https://github.com/wundii/data-mapper-symfony-bundle/actions/workflows/code_quality.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%2010-brightgreen.svg?style=for-the-badge)](https://phpstan.org/)
![VERSION](https://img.shields.io/packagist/v/wundii/data-mapper-symfony-bundle?style=for-the-badge)
[![PHP](https://img.shields.io/packagist/php-v/wundii/data-mapper-symfony-bundle?style=for-the-badge)](https://www.php.net/)
[![Rector](https://img.shields.io/badge/Rector-8.2-blue.svg?style=for-the-badge)](https://getrector.com)
[![ECS](https://img.shields.io/badge/ECS-check-blue.svg?style=for-the-badge)](https://tomasvotruba.com/blog/zen-config-in-ecs)
[![PHPUnit](https://img.shields.io/badge/PHP--Unit-check-blue.svg?style=for-the-badge)](https://phpunit.org)
[![codecov](https://img.shields.io/codecov/c/github/wundii/data-mapper-symfony-bundle/main?token=V61OLHU8X3&style=for-the-badge)](https://codecov.io/github/wundii/data-mapper-symfony-bundle)
[![Downloads](https://img.shields.io/packagist/dt/wundii/data-mapper-symfony-bundle.svg?style=for-the-badge)](https://packagist.org/packages/wundii/data-mapper-symfony-bundle)

A Symfony integration for [wundii/data-mapper](https://github.com/wundii/data-mapper). 
This library is an extremely fast and strictly typed object mapper built for modern PHP (8.2+). 
It seamlessly transforms data from formats like JSON, NEON, XML, YAML, arrays, and standard objects into well-structured PHP objects.

Ideal for developers who need reliable and efficient data mapping without sacrificing code quality or modern best practices.

## Features
- Mapping source data into objects
- Mapping source data with a list of elements into a list of objects
- Initialize object via constructor, properties or methods
- Map nested objects, arrays of objects
- Class mapping for interfaces or other classes
- Custom root element for starting with the source data
- Auto-casting for `float` types (eu to us decimal separator)
- Target alias via Attribute for properties and methods

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

## Use as Symfony DataMapper version
```php
<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\TestClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Wundii\DataMapper\SymfonyBundle\DataMapper;

final class YourController extends AbstractController
{
    public function __construct(
        private readonly DataMapper $dataMapper,
    ) {
    }

    #[Route('/do-something/', name: 'app_do-something')]
    public function doSomething(Request $request): Response
    {
        // Automatic recognition of the format based on the content type of the request
        // returns an instance of TestClass or an Exception
        $testClass = $this->dataMapper->request($request, TestClass::class);
        
        // or you can use tryRequest to avoid exceptions, null will be returned instead
        $testClass = $this->dataMapper->tryRequest($request, TestClass::class);
        $this->dataMapper->getMapStatusEnum();
        $this->dataMapper->getErrorMessage();
        
        // Do something with $testClass
        
        return $this->json(...);
    }
}
```

## Use as native DataMapper version
```php
<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\TestClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Wundii\DataMapper\DataMapper;

final class YourController extends AbstractController
{
    public function __construct(
        private readonly DataMapper $dataMapper,
    ) {
    }

    #[Route('/do-something/', name: 'app_do-something')]
    public function doSomething(Request $request): Response
    {
        // you can use the native DataMapper methods directly
        $testClass = $this->dataMapper->json($request->getContent(), TestClass::class);
        
        // Do something with $testClass
        
        return $this->json(...);
    }
}
```