<?php

namespace Support\Transformers;

use Domain\Discounts\Contracts\Services\DiscountsService;
use League\Fractal\TransformerAbstract as Transformer;

class DiscountsServiceDiscountTransformer extends Transformer
{
    /**
     * @param int $id
     * @return array
     */
    public function transform(int $id): array
    {
        $entity = app(DiscountsService::class)->find($id);

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
