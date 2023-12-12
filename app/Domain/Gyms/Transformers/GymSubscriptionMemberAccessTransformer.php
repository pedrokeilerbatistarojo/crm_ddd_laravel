<?php

namespace Domain\Gyms\Transformers;

use Domain\Gyms\Models\GymSubscriptionMemberAccess;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;
use Support\Transformers\Traits\GymSubscriptionMember;

class GymSubscriptionMemberAccessTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;
    use GymSubscriptionMember;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser',
        'gymSubscriptionMember'
    ];

    /**
     * @param GymSubscriptionMemberAccess $entity
     * @return array
     */
    public function transform(GymSubscriptionMemberAccess $entity): array
    {
        return [
            'id' => $entity->id,
            'member_id' => $entity->member_id,
            'date_from' => $entity->date_from,
            'date_to' => $entity->date_to,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at

        ];
    }
}
