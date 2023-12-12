<?php

namespace Domain\Gyms\Transformers;

use Domain\Gyms\Models\GymSubscriptionMember;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;
use Support\Transformers\Traits\Client;
use Support\Transformers\Traits\GymSubscription;

class GymSubscriptionMemberTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;
    use Client;
    use GymSubscription;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser',
        'client',
        'gymSubscription'
    ];

    /**
     * @param GymSubscriptionMember $entity
     * @return array
     */
    public function transform(GymSubscriptionMember $entity): array
    {
        return [
            'id' => $entity->id,
            'gym_subscription_id' => $entity->gym_subscription_id,
            'client_id' => $entity->client_id,
            'date_from' => $entity->date_from,
            'date_to' => $entity->date_to,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];
    }
}
