<?php

namespace Support\Transformers\Traits;

use League\Fractal\Resource\Item;
use Support\Transformers\OrdersServiceOrderTransformer;

trait Order
{
    /**
     * @param mixed $entity
     * @return Item|null
     */
    public function includeOrder(mixed $entity): ?Item
    {
        return $entity->order_id ? $this->item(
            (int)$entity->order_id,
            app(OrdersServiceOrderTransformer::class)
        ) : null;
    }
}
