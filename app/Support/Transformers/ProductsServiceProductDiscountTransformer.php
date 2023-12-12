<?php

namespace Support\Transformers;

use Domain\Products\Contracts\Services\ProductsService;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\Discount;
use Support\Transformers\Traits\LastModifiedByUser;

class ProductsServiceProductDiscountTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;
    use Discount;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser',
        'discount'
    ];

    /**
     * @param int $id
     * @return array
     */
    public function transform(int $id): array
    {
        $entity = app(ProductsService::class)->findProductDiscount($id);

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
