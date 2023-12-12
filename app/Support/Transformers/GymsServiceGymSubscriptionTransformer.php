<?php

namespace Support\Transformers;

use Domain\Gyms\Contracts\Services\GymsService;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;

class GymsServiceGymSubscriptionTransformer extends Transformer
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
        $entity = app(GymsService::class)->findGymSubscription($id);

        $this->entityData = [
            'id' => $entity->id,
            'client_id' => $entity->client_id,
            'gym_fee_type_id' => $entity->gym_fee_type_id,
            'gym_fee_type_name' => $entity->gym_fee_type_name,
            'price' => $entity->price,
            'activation_date' => $entity->activation_date,
            'start_date' => $entity->start_date,
            'end_date' => $entity->end_date,
            'expiration_date' => $entity->expiration_date,
            'payment_day' => $entity->payment_day,
            'biweekly_payment_day' => $entity->biweekly_payment_day,
            'payment_type' => $entity->payment_type,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];

        return $this->entityData;
    }
}
