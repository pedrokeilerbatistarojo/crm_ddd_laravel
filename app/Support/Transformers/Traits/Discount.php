<?php

namespace Support\Transformers\Traits;

use League\Fractal\Resource\Item;
use Support\Transformers\DiscountsServiceDiscountTransformer;

trait Discount
{
    /**
     * @param mixed $entity
     * @return Item|null
     */
    public function includeDiscount(mixed $entity): ?Item
    {
        return $entity->discount_id ? $this->item(
            (int)$entity->discount_id,
            app(DiscountsServiceDiscountTransformer::class)
        ) : null;
    }
}
