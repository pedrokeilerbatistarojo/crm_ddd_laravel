<?php

namespace Domain\Products\Transformers;

use Domain\Products\Models\ProductDiscount;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\Discount;
use Support\Transformers\Traits\LastModifiedByUser;

class ProductDiscountTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;
    use Discount;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser',
        'discount',
    ];

    /**
     * @param ProductDiscount $entity
     * @return array
     */
    public function transform(ProductDiscount $entity): array
    {
        return [
            'id' => $entity->id,
            'product_id' => $entity->product_id,
            'discount_id' => $entity->discount_id,
            'price' => $entity->price,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];
    }

}
