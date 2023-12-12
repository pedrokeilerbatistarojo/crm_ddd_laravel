<?php

namespace Domain\Products\Transformers;

use Domain\Products\Models\Category;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;

class CategoryTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser'
    ];

    /**
     * @param Category $entity
     * @return array
     */
    public function transform(Category $entity): array
    {
        return [
            'id' => $entity->id,
            'name' => $entity->name,
            'active' => $entity->active,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];
    }
}
