<?php

namespace Domain\Localities\Transformers;

use Domain\Localities\Models\Province;
use League\Fractal\TransformerAbstract as Transformer;

class ProvinceTransformer extends Transformer
{
    protected array $availableIncludes = [
    ];

    /**
     * @param Province $entity
     * @return array
     */
    public function transform(Province $entity): array
    {
        return [
            'id' => $entity->id,
            'name' => $entity->name
        ];
    }
}
