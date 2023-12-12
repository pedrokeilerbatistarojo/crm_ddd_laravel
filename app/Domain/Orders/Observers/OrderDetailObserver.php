<?php

namespace Domain\Orders\Observers;

use Domain\Orders\Actions\RecalculateOrderTotal;
use Domain\Orders\Models\OrderDetail;

class OrderDetailObserver
{
    public function created(OrderDetail $entity): void
    {
        $this->calculateOrderTotal($entity);
    }

    public function updated(OrderDetail $entity): void
    {
        $this->calculateOrderTotal($entity);
    }

    public function deleted(OrderDetail $entity): void
    {
        $this->calculateOrderTotal($entity);
    }

    protected function calculateOrderTotal(OrderDetail $entity): void
    {
        app(RecalculateOrderTotal::class)(['id' => $entity->order_id]);
    }

}
