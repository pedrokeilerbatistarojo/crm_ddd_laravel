<?php

namespace Support\Transformers\Traits;

use League\Fractal\Resource\Item;
use Support\Transformers\UsersServiceUserTransformer;

trait CreatedByUser
{
    /**
     * @param mixed $entity
     * @return Item|null
     */
    public function includeCreatedByUser(mixed $entity): ?Item
    {
        return $entity->created_by ? $this->item(
            (int)$entity->created_by,
            app(UsersServiceUserTransformer::class)
        ) : null;
    }
}
