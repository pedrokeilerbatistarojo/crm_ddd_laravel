<?php

namespace Domain\Gyms\Transformers;

use Domain\Gyms\Models\GymFeeType;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;

class GymFeeTypeTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser',
    ];

    /**
     * @param GymFeeType $entity
     * @return array
     */
    public function transform(GymFeeType $entity): array
    {
        return [
            'id' => $entity->id,
            'name' => $entity->name,
            'price' => $entity->price,
            'period_type' => $entity->period_type->value,
            'payment_day' => $entity->payment_day,
            'duration_number_of_days' => $entity->duration_number_of_days,
            'biweekly_payment_day' => $entity->biweekly_payment_day,
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
