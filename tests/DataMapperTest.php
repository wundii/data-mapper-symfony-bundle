<?php

declare(strict_types=1);

namespace Wundii\DataMapper\SymfonyBundle\Tests;

use Integration\Objects\Types\TypeString;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Wundii\DataMapper\Exception\DataMapperInvalidArgumentException;
use Wundii\DataMapper\SymfonyBundle\DataMapper;

class DataMapperTest extends TestCase
{
    private DataMapper $dataMapper;

    protected function setUp(): void
    {
        $this->dataMapper = new DataMapper();
    }

    /**
     * @throws Exception
     */
    public function getRequest(
        string $contentType,
        string $content
    ): Request|MockObject {
        $headerBag = $this->createMock(HeaderBag::class);
        $headerBag->expects($this->once())
            ->method('get')
            ->with('Content-Type')
            ->willReturn($contentType);

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('getContent')
            ->willReturn($content);

        $request->headers = $headerBag;

        return $request;

    }

    public function testRequestWithInvalidContentTypes(): void
    {
        $this->expectException(DataMapperInvalidArgumentException::class);

        $content = '{"name": "test"}';
        $object = TypeString::class;

        $request = $this->getRequest('fail', $content);
        $result = $this->dataMapper->request($request, $object);

        $this->assertSame($object, $result);
    }

    public function testRequestWithValidContentTypeJson(): void
    {
        $this->expectException(DataMapperInvalidArgumentException::class);

        $content = '{"string": "test"}';
        $object = TypeString::class;

        $request = $this->getRequest('application/json', $content);
        $result = $this->dataMapper->request($request, $object);

        $this->assertSame($object, $result);
    }

    public function testRequestWithValidContentTypeNeon(): void
    {
        $this->expectException(DataMapperInvalidArgumentException::class);

        $content = 'string: "Nostromo"';
        $object = TypeString::class;

        $request = $this->getRequest('application/neon', $content);
        $result = $this->dataMapper->request($request, $object);

        $this->assertSame($object, $result);
    }

    public function testRequestWithValidContentTypeXml(): void
    {
        $this->expectException(DataMapperInvalidArgumentException::class);

        $content = '<?xml version="1.0" encoding="UTF-8" ?>' .
            '<root><string>Nostromo</string></root>';
        $object = TypeString::class;

        $request = $this->getRequest('application/xml', $content);
        $result = $this->dataMapper->request($request, $object);

        $this->assertSame($object, $result);
    }

    public function testRequestWithValidContentTypeYaml(): void
    {
        $this->expectException(DataMapperInvalidArgumentException::class);

        $content = 'string: "Nostromo"';
        $object = TypeString::class;

        $request = $this->getRequest('application/yaml', $content);
        $result = $this->dataMapper->request($request, $object);

        $this->assertSame($object, $result);
    }
}
