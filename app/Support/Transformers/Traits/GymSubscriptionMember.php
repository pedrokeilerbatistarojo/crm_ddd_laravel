<?php

namespace Support\Transformers\Traits;

use League\Fractal\Resource\Item;
use Support\Transformers\GymsServiceGymSubscriptionMemberTransformer;

trait GymSubscriptionMember
{
    /**
     * @param mixed $entity
     * @return Item|null
     */
    public function includeGymSubscriptionMember(mixed $entity): ?Item
    {
        return $entity->member_id ? $this->item(
            (int)$entity->member_id,
            app(GymsServiceGymSubscriptionMemberTransformer::class)
        ) : null;
    }
}
