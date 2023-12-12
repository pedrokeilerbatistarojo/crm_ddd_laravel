<?php

namespace Support\Transformers;

use Domain\Orders\Contracts\Services\OrdersService;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;

class OrdersServiceOrderDetailTransformer extends Transformer
{
    /**
     * @var array|string[]
     */
    protected array $availableIncludes = [
        'order',
        'product',
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
        $entity = app(OrdersService::class)->findDetail($id);

        $this->entityData = [
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
            'created_at' => $entity->created_at,
            'update_at' => $entity->updated_at
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

    /**
     * @param int $id
     * @return Item|null
     */
    public function includeProduct(int $id): ?Item
    {
        return !empty($this->entityData['product_id']) ? $this->item(
            (int)$this->entityData['product_id'],
            app(ProductsServiceProductTransformer::class)
        ) : null;
    }
}
