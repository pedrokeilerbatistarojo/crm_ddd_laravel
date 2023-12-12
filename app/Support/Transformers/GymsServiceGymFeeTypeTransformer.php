<?php

namespace Support\Transformers;

use Domain\Gyms\Contracts\Services\GymsService;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;

class GymsServiceGymFeeTypeTransformer extends Transformer
{
    /**
     * @var array
     */
    protected array $availableIncludes = [
        'client'
    ];

    /**
     * @var array
     */
    protected array $entityData = [];

    /**
     * @param int $id
     * @return array
     */
    public function transform(int $id): array
    {
        $entity = app(GymsService::class)->findGymFeeType($id);

        $this->entityData = [
            'id' => $entity->id,
            'name' => $entity->name,
            'price' => $entity->price,
            'period_type' => $entity->period_type,
            'payment_day' => $entity->payment_day,
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
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];

        return $this->entityData;
    }
}
