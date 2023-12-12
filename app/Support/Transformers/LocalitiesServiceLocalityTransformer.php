<?php

namespace Support\Transformers;

use Domain\Localities\Contracts\Services\LocalitiesService;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;

class LocalitiesServiceLocalityTransformer extends Transformer
{
    /**
     * @var array|string[]
     */
    protected array $availableIncludes = [
        'province'
    ];

    /**
     * @var array
     */
    protected array $entityData = [];

    /**
     * @param int $id
     * @return array
     */
    public function transform(int $id): array
    {
        $entity = app(LocalitiesService::class)->find($id);

        $this->entityData = [
            'id' => $entity->id,
            'zip_code' => $entity->zip_code,
            'municipio_id' => $entity->municipio_id,
            'locality' => $entity->locality,
            'population_unit_code' => $entity->population_unit_code,
            'singular_entity_name' => $entity->singular_entity_name,
            'population' => $entity->population,
            'province_id' => $entity->province_id
        ];

        return $this->entityData;
    }

    /**
     * @param int $id
     * @return Item|null
     */
    public function includeProvince(int $id): ?Item
    {
        return !empty($this->entityData['province_id']) ? $this->item(
            (int)$this->entityData['province_id'],
            app(LocalitiesServiceProvinceTransformer::class)
        ) : null;
    }
}
