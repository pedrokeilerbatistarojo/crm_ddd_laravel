<?php

namespace Support\Transformers;

use Domain\Payments\Contracts\Services\PaymentsService;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;

class PaymentsServicePaymentTransformer extends Transformer
{
    /**
     * @var array
     */
    protected array $availableIncludes = [
        'order'
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
        $entity = app(PaymentsService::class)->find($id);

        $this->entityData = [
            'id' => $entity->id,
            'order_id' => $entity->order_id,
            'due_date' => $entity->due_date,
            'paid_date' => $entity->paid_date,
            'type' => $entity->type,
            'amount' => $entity->amount,
            'paid_amount' => $entity->paid_amount,
            'returned_amount' => $entity->returned_amount,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];

        return $this->entityData;
    }

    /**
     * @param int $id
     * @return Item|null
     */
    public function includeOrder(int $id): ?Item
    {
        return !empty($this->entityData['order_id']) ? $this->item(
            (int)$this->entityData['order_id'],
            app(OrdersServiceOrderTransformer::class)
        ) : null;
    }
}
