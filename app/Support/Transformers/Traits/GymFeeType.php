<?php

namespace Support\Transformers\Traits;

use League\Fractal\Resource\Item;
use Support\Transformers\GymsServiceGymFeeTypeTransformer;

trait GymFeeType
{
    /**
     * @param mixed $entity
     * @return Item|null
     */
    public function includeGymFeeType(mixed $entity): ?Item
    {
        return $entity->gym_fee_type_id ? $this->item(
            (int)$entity->gym_fee_type_id,
            app(GymsServiceGymFeeTypeTransformer::class)
        ) : null;
    }
}
