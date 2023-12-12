<?php

namespace Domain\Gyms\Transformers;

use Domain\Gyms\Models\GymSubscriptionQuota;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\GymSubscription;
use Support\Transformers\Traits\LastModifiedByUser;

class GymSubscriptionQuotaTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;
    use GymSubscription;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser',
        'gymSubscription'
    ];

    /**
     * @param GymSubscriptionQuota $entity
     * @return array
     */
    public function transform(GymSubscriptionQuota $entity): array
    {
        return [
            'id' => $entity->id,
            'gym_subscription_id' => $entity->gym_subscription_id,
            'amount' => $entity->amount,
            'date' => $entity->date,
            'state' => $entity->state->value,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at

        ];
    }
}
