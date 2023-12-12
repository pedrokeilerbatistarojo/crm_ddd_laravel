<?php

namespace Domain\Localities\Transformers;

use Domain\Localities\Models\Locality;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;

class LocalityTransformer extends Transformer
{
    protected array $availableIncludes = [
        'province'
    ];

    /**
     * @param Locality $entity
     * @return array
     */
    public function transform(Locality $entity): array
    {
        return [
            'id' => $entity->id,
            'zip_code' => $entity->zip_code,
            'municipio_id' => $entity->municipio_id,
            'locality' => $entity->locality,
            'population_unit_code' => $entity->population_unit_code,
            'singular_entity_name' => $entity->singular_entity_name,
            'population' => $entity->population,
            'province_id' => $entity->province_id
        ];
    }

    /**
     * @param Locality $entity
     * @return Item|null
     */
    public function includeProvince(Locality $entity): ?Item
    {
        $province = $entity->province;

        return $province ? $this->item($province, app(ProvinceTransformer::class)) : null;
    }
}
