<?php

namespace Domain\Companies\Services;

use Domain\Companies\Contracts\Repositories\CompaniesRepository;
use Domain\Companies\DataTransferObjects\CompanyEntity;
use Domain\Companies\Models\Company;
use Domain\Companies\Transformers\CompanyTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Core\Serializers\FractalDataArraySerializer;

class CompaniesService implements \Domain\Companies\Contracts\Services\CompaniesService
{
    /**
     * @var CompaniesRepository
     */
    protected CompaniesRepository $repository;

    /**
     * @param CompaniesRepository $repository
     */
    public function __construct(CompaniesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws UnknownProperties
     */
    public function find(int $id, array $includes = []): ?CompanyEntity
    {
        if (!$record = $this->repository->find($id)) {
            return null;
        }

        return $this->DTOFromModel($record, $includes);
    }

    /**
     * @throws UnknownProperties
     */
    private function DTOFromModel(Company $entity, array $includes = []): CompanyEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(CompanyTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new CompanyEntity($data);
    }
}
