<?php

namespace Domain\Gyms\Transformers;

use Domain\Gyms\Models\GymSubscriptionMemberAccessRight;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;
use Support\Transformers\Traits\GymSubscriptionMember;

class GymSubscriptionMemberAccessRightTransformer extends Transformer
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
     * @param GymSubscriptionMemberAccessRight $entity
     * @return array
     */
    public function transform(GymSubscriptionMemberAccessRight $entity): array
    {
        return [
            'id' => $entity->id,
            'member_id' => $entity->member_id,
            'date_from' => $entity->date_from,
            'date_to' => $entity->date_to,
            'hour_from' => $entity->hour_from,
            'hour_to' => $entity->hour_to,
            'monday_access' => $entity->monday_access,
            'tuesday_access' => $entity->tuesday_access,
            'wednesday_access' => $entity->wednesday_access,
            'thursday_access' => $entity->thursday_access,
            'friday_access' => $entity->friday_access,
            'saturday_access' => $entity->saturday_access,
            'sunday_access' => $entity->sunday_access,
            'unlimited_access' => $entity->unlimited_access,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at

        ];
    }
}
