<?php

namespace Domain\CircuitReservations\Transformers;

use Domain\CircuitReservations\Contracts\Repositories\CircuitReservationsRepository;
use Domain\CircuitReservations\Models\CircuitReservation;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\OrdersServiceOrderDetailTransformer;
use Support\Transformers\Traits\Client;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;

class CircuitReservationTransformer extends Transformer
{
    use Client;
    use CreatedByUser;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'client',
        'createdByUser',
        'lastModifiedByUser',
        'orderDetails'
    ];

    /**
     * @param CircuitReservation $entity
     * @return array
     */
    public function transform(CircuitReservation $entity): array
    {
        return [
            'id' => $entity->id,
            'client_id' => $entity->client_id,
            'date' => $entity->date,
            'time' => $entity->time,
            'duration' => $entity->duration,
            'adults' => $entity->adults,
            'children' => $entity->children,
            'used' => $entity->used,
            'notes' => $entity->notes,
            'schedule_note' => $entity->schedule_note,
            'treatment_reservations'=> $entity->treatment_reservations,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];
    }

    /**
     * @param CircuitReservation $entity
     * @return Collection|null
     */
    public function includeOrderDetails(CircuitReservation $entity): ?Collection
    {
        $ids = app(CircuitReservationsRepository::class)->relatedOrderDetails($entity->id)->pluck('order_detail_id')->values()->toArray();

        return count($ids) ? $this->collection($ids, app(OrdersServiceOrderDetailTransformer::class)) : null;
    }
}
