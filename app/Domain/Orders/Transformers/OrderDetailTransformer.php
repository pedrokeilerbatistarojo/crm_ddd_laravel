<?php

namespace Domain\Orders\Transformers;

use Domain\CircuitReservations\Contracts\Services\CircuitReservationsService;
use Domain\Orders\Models\OrderDetail;
use Domain\TreatmentReservations\Contracts\Services\TreatmentReservationsService;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Transformers\CircuitReservationsServiceCircuitReservationTransformer;
use Support\Transformers\ProductsServiceProductTransformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;
use Support\Transformers\TreatmentReservationsServiceTreatmentReservationTransformer;

class OrderDetailTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser',
        'order',
        'product',
        'circuitReservations',
        'treatmentReservations'
    ];

    /**
     * @param OrderDetail $entity
     * @return array
     */
    public function transform(OrderDetail $entity): array
    {
        return [
            'id' => $entity->id,
            'order_id' => $entity->order_id,
            'product_id' => $entity->product_id,
            'product_name' => $entity->product_name,
            'price' => $entity->price,
            'quantity' => $entity->quantity,
            'circuit_sessions' => $entity->circuit_sessions,
            'treatment_sessions' => $entity->treatment_sessions,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $entity->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param OrderDetail $entity
     * @return Item|null
     */
    public function includeOrder(OrderDetail $entity): ?Item
    {
        $order = $entity->order;

        return $order ? $this->item($order, app(OrderTransformer::class)) : null;
    }

    /**
     * @param OrderDetail $entity
     * @return Item|null
     */
    public function includeProduct(OrderDetail $entity): ?Item
    {
        return $entity->product_id ? $this->item(
            $entity->product_id,
            app(ProductsServiceProductTransformer::class)
        ) : null;
    }

    /**
     * @param OrderDetail $entity
     * @return Collection|null
     * @throws UnknownProperties
     */
    public function includeCircuitReservations(OrderDetail $entity): ?Collection
    {
        $reservations = app(CircuitReservationsService::class)->findByOrderDetail($entity->id);

        return $reservations->getData()->count() ? $this->collection(
            $reservations->getData()->pluck('id')->values()->toArray(),
            app(CircuitReservationsServiceCircuitReservationTransformer::class)
        ) : null;
    }

    /**
     * @param OrderDetail $entity
     * @return Collection|null
     * @throws UnknownProperties
     */
    public function includeTreatmentReservations(OrderDetail $entity): ?Collection
    {
        $reservations = app(TreatmentReservationsService::class)->findByOrderDetail($entity->id);

        return $reservations->getData()->count() ? $this->collection(
            $reservations->getData()->pluck('id')->values()->toArray(),
            app(TreatmentReservationsServiceTreatmentReservationTransformer::class)
        ) : null;
    }
}
