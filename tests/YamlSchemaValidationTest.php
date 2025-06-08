<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use Wundii\DataMapper\SymfonyBundle\Tests\XsdSchemaValidationTest;

class YamlSchemaValidationTest extends TestCase
{
    public function testServicesYamlIsValidYaml(): void
    {
        $path = __DIR__ . '/../src/Resources/config/packages/data_mapper.yaml';
        $this->assertFileExists($path);

        try {
            $parsed = Yaml::parseFile($path);
        } catch (ParseException $e) {
            $this->fail('services.yaml is not valid YAML: ' . $e->getMessage());
        }

        $this->assertIsArray($parsed);

        $namespace = 'http://wundii.com/schema/dic/data_mapper';
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $config = $dom->createElementNS($namespace, 'config');
        $dom->appendChild($config);
        $this->arrayToXml($parsed['data_mapper'], $config, $dom, $namespace);

        $xml = $dom->saveXML();

        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml);

        libxml_use_internal_errors(true);
        $isValid = $dom->schemaValidate(XsdSchemaValidationTest::SCHEMA_PATH);

        $errors = libxml_get_errors();
        libxml_clear_errors();

        $this->assertTrue($isValid, XsdSchemaValidationTest::formatLibxmlErrors($errors));
    }

    /**
     * @throws DOMException
     */
    public function arrayToXml(
        array $data,
        DOMElement $parent,
        DOMDocument $dom,
        string $namespace
    ): void {
        foreach ($data as $key => $value) {
            if ($key === 'class_map' && is_array($value)) {
                $classMap = $dom->createElementNS($namespace, 'class_map');
                foreach ($value as $entryKey => $entryValue) {
                    $entry = $dom->createElementNS($namespace, 'entry', $entryValue);
                    $entry->setAttribute('key', $entryKey);
                    $classMap->appendChild($entry);
                }
                $parent->appendChild($classMap);
                continue;
            }

            if (is_array($value)) {
                $child = $dom->createElementNS($namespace, $key);
                $this->arrayToXml($value, $child, $dom, $namespace);
                $parent->appendChild($child);
            } else {
                $child = $dom->createElementNS($namespace, $key, $value);
                $parent->appendChild($child);
            }
        }
    }
}
