<?php

namespace Domain\Discounts\Transformers;

use Domain\Discounts\Models\Discount;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;

class DiscountTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser'
    ];

    /**
     * @param Discount $entity
     * @return array
     */
    public function transform(Discount $entity): array
    {
        return [
            'id' => $entity->id,
            'name' => $entity->name,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];
    }
}
