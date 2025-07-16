<?php

declare(strict_types=1);

namespace Wundii\DataMapper\SymfonyBundle;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Wundii\DataMapper\DataMapper as BaseDataMapper;
use Wundii\DataMapper\Dto\CsvDto;
use Wundii\DataMapper\Enum\SourceTypeEnum;
use Wundii\DataMapper\Exception\DataMapperException;
use Wundii\DataMapper\SymfonyBundle\Enum\MapStatusEnum;

/**
 * @template T of object
 * @extends BaseDataMapper<T>
 */
class DataMapper extends BaseDataMapper
{
    private ?string $errorMessage = null;

    private MapStatusEnum $mapStatusEnum = MapStatusEnum::AWAIT;

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getMapStatusEnum(): MapStatusEnum
    {
        return $this->mapStatusEnum;
    }

    /**
     * @param class-string<T>|T $object
     * @param string[] $rootElementTree
     * @param bool $forceInstance // create a new instance, if no data can be found for the object
     * @param string[] $options
     * @return ($object is class-string ? T : T[])
     */
    public function request(
        Request $request,
        string|object $object,
        array $rootElementTree = [],
        bool $forceInstance = false,
        array $options = [],
    ): object|array {
        $content = $request->getContent();
        $sourceTypeEnum = match ($request->headers->get('Content-Type')) {
            'application/csv', 'text/csv' => SourceTypeEnum::CSV,
            'application/json' => SourceTypeEnum::JSON,
            'application/neon', 'text/neon' => SourceTypeEnum::NEON,
            'application/xml', 'text/xml' => SourceTypeEnum::XML,
            'application/yaml', 'text/yaml' => SourceTypeEnum::YAML,
            default => throw DataMapperException::InvalidArgument('Unsupported content type'),
        };

        if ($content === '' && ! $forceInstance) {
            throw DataMapperException::InvalidArgument('No content provided in request');
        }

        if ($sourceTypeEnum === SourceTypeEnum::CSV) {
            $csvDto = new CsvDto(
                $content,
                $options['separator'] ?? CsvDto::DEFAULT_SEPARATOR,
                $options['enclosure'] ?? CsvDto::DEFAULT_ENCLOSURE,
                $options['escape'] ?? CsvDto::DEFAULT_ESCAPE,
                (int) ($options['headerLine'] ?? CsvDto::DEFAULT_HEADER_LINE),
                (int) ($options['firstLine'] ?? CsvDto::DEFAULT_FIRST_LINE),
            );
            $content = $csvDto;
        }

        return $this->map($sourceTypeEnum, $content, $object, $rootElementTree, $forceInstance);
    }

    /**
     * @param class-string<T>|T $object
     * @param string[] $rootElementTree
     * @param bool $forceInstance // create a new instance, if no data can be found for the object
     * @param string[] $options
     * @return ($object is class-string ? T : T[])
     */
    public function tryRequest(
        Request $request,
        string|object $object,
        array $rootElementTree = [],
        bool $forceInstance = false,
        array $options = [],
    ): object|array|null {
        /**
         * reset error message
         */
        $this->errorMessage = null;

        try {
            $this->mapStatusEnum = MapStatusEnum::SUCCESS;
            return $this->request($request, $object, $rootElementTree, $forceInstance, $options);
        } catch (Exception $exception) {
            $this->errorMessage = $exception->getMessage();
            $this->mapStatusEnum = MapStatusEnum::ERROR;
            return null;
        }
    }
}
