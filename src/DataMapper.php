<?php

declare(strict_types=1);

namespace Wundii\DataMapper\SymfonyBundle;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Wundii\DataMapper\DataMapper as BaseDataMapper;
use Wundii\DataMapper\Enum\SourceTypeEnum;
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
     * @return ($object is class-string ? T : T[])
     */
    public function request(
        Request $request,
        string|object $object,
        array $rootElementTree = [],
        bool $forceInstance = false,
    ): object|array|null {
        /**
         * reset error message and status
         */
        $this->errorMessage = null;
        $this->mapStatusEnum = MapStatusEnum::AWAIT;

        $content = $request->getContent();
        $sourceTypeEnum = match ($request->headers->get('Content-Type')) {
            'application/json' => SourceTypeEnum::JSON,
            'application/neon', 'text/neon' => SourceTypeEnum::NEON,
            'application/xml', 'text/xml' => SourceTypeEnum::XML,
            'application/yaml', 'text/yaml' => SourceTypeEnum::YAML,
            default => null,
        };

        if ($sourceTypeEnum === null) {
            $this->errorMessage = 'Unsupported content type';
            $this->mapStatusEnum = MapStatusEnum::ERROR;
            return null;
        }

        if ($content === '' && ! $forceInstance) {
            $this->errorMessage = 'No content provided in request';
            $this->mapStatusEnum = MapStatusEnum::ERROR;
            return null;
        }

        try {
            $this->mapStatusEnum = MapStatusEnum::SUCCESS;
            return $this->map($sourceTypeEnum, $content, $object, $rootElementTree, $forceInstance);
        } catch (Exception $exception) {
            $this->errorMessage = $exception->getMessage();
            $this->mapStatusEnum = MapStatusEnum::ERROR;
            return null;
        }
    }
}
