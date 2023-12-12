<?php

namespace Support\Transformers\Traits;

use League\Fractal\Resource\Item;
use Support\Transformers\GymsServiceGymSubscriptionTransformer;

trait GymSubscription
{
    /**
     * @param mixed $entity
     * @return Item|null
     */
    public function includeGymSubscription(mixed $entity): ?Item
    {
        return $entity->gym_subscription_id ? $this->item(
            (int)$entity->gym_subscription_id,
            app(GymsServiceGymSubscriptionTransformer::class)
        ) : null;
    }
}
