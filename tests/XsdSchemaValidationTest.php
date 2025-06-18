<?php

declare(strict_types=1);

namespace Wundii\DataMapper\SymfonyBundle\Tests;

use DOMDocument;
use LibXMLError;
use PHPUnit\Framework\TestCase;

class XsdSchemaValidationTest extends TestCase
{
    public const SCHEMA_PATH = __DIR__ . '/../src/Resources/config/schema/datamapper-1.0.xsd';

    public function testValidXmlPassesXsdValidation(): void
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<config>
    <approach>CONSTRUCTOR</approach>
    <accessible>PUBLIC</accessible>
    <class_map>
        <entry key="App\\Dto\\UserDto">App\\Entity\\User</entry>
        <entry key="App\\Dto\\ProductDto">App\\Entity\\Product</entry>
    </class_map>
</config>
XML;

        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml);

        libxml_use_internal_errors(true);
        $isValid = $dom->schemaValidate(self::SCHEMA_PATH);

        $errors = libxml_get_errors();
        libxml_clear_errors();

        $this->assertTrue($isValid, self::formatLibxmlErrors($errors));
    }

    /**
     * @param LibXMLError[] $errors
     */
    public static function formatLibxmlErrors(array $errors): string
    {
        return implode("\n", array_map(function (LibXMLError $error) {
            return trim($error->message) . ' on line ' . $error->line;
        }, $errors));
    }
}
