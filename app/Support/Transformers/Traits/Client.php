<?php

namespace Support\Transformers\Traits;

use League\Fractal\Resource\Item;
use Support\Transformers\ClientsServiceClientTransformer;

trait Client
{
    /**
     * @param mixed $entity
     * @return Item|null
     */
    public function includeClient(mixed $entity): ?Item
    {
        return $entity->client_id ? $this->item(
            (int)$entity->client_id,
            app(ClientsServiceClientTransformer::class)
        ) : null;
    }
}
