<?php

namespace Support\Transformers\Traits;

use League\Fractal\Resource\Item;
use Support\Transformers\UsersServiceUserTransformer;

trait LastModifiedByUser
{
    /**
     * @param mixed $entity
     * @return Item|null
     */
    public function includeLastModifiedByUser(mixed $entity): ?Item
    {
        return $entity->last_modified_by ? $this->item(
            (int)$entity->last_modified_by,
            app(UsersServiceUserTransformer::class)
        ) : null;
    }
}
