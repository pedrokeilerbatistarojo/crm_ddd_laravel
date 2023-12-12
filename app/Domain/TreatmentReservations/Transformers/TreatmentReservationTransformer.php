<?php

namespace Domain\TreatmentReservations\Transformers;

use Domain\TreatmentReservations\Contracts\Repositories\TreatmentReservationsRepository;
use Domain\TreatmentReservations\Models\TreatmentReservation;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\OrdersServiceOrderDetailTransformer;
use Support\Transformers\Traits\Client;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;

class TreatmentReservationTransformer extends Transformer
{
    use Client;
    use CreatedByUser;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'client',
        'employee',
        'createdByUser',
        'lastModifiedByUser',
        'orderDetails'
    ];

    /**
     * @param TreatmentReservation $entity
     * @return array
     */
    public function transform(TreatmentReservation $entity): array
    {
        return [
            'id' => $entity->id,
            'client_id' => $entity->client_id,
            'employee_id' => $entity->employee_id,
            'date' => $entity->date,
            'time' => $entity->time,
            'duration' => $entity->duration,
            'used' => $entity->used,
            'notes' => $entity->notes,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];
    }

    /**
     * @param mixed $entity
     * @return Item|null
     */
    public function includeEmployee(mixed $entity): ?Item
    {
        return $entity->employee_id ? $this->item(
            (int)$entity->employee_id,
            app(TreatmentReservationEmployeeTransformer::class)
        ) : null;
    }

    /**
     * @param TreatmentReservation $entity
     * @return Collection|null
     */
    public function includeOrderDetails(TreatmentReservation $entity): ?Collection
    {
        $ids = app(TreatmentReservationsRepository::class)->relatedOrderDetails($entity->id)->pluck(
            'order_detail_id'
        )->values()->toArray();

        return count($ids) ? $this->collection($ids, app(OrdersServiceOrderDetailTransformer::class)) : null;
    }
}
