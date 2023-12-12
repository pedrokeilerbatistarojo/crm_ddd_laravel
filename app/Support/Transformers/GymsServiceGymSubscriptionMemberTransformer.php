<?php

namespace Support\Transformers;

use Domain\Gyms\Contracts\Services\GymsService;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;

class GymsServiceGymSubscriptionMemberTransformer extends Transformer
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
        $entity = app(GymsService::class)->findGymSubscriptionMember($id);

        $this->entityData = [
            'id' => $entity->id,
            'gym_subscription_id' => $entity->gym_subscription_id,
            'client_id' => $entity->client_id,
            'date_from' => $entity->date_from,
            'date_to' => $entity->date_to,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];

        return $this->entityData;
    }
}
