<?php

namespace Support\Transformers;

use Domain\Localities\Contracts\Services\LocalitiesService;
use League\Fractal\TransformerAbstract as Transformer;

class LocalitiesServiceProvinceTransformer extends Transformer
{
    /**
     * @var array|string[]
     */
    protected array $availableIncludes = [];

    /**
     * @param int $id
     * @return array
     */
    public function transform(int $id): array
    {
        $entity = app(LocalitiesService::class)->findProvince($id);

        return [
            'id' => $entity->id,
            'name' => $entity->name
        ];
    }
}
