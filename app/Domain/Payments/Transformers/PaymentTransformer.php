<?php

namespace Domain\Payments\Transformers;

use Domain\Payments\Models\Payment;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;
use Support\Transformers\Traits\Order;

class PaymentTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;
    use Order;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser',
        'order'
    ];

    /**
     * @param Payment $entity
     * @return array
     */
    public function transform(Payment $entity): array
    {
        return [
            'id' => $entity->id,
            'order_id' => $entity->order_id,
            'due_date' => $entity->due_date->toDateTimeString(),
            'paid_date' => $entity->paid_date->toDateTimeString(),
            'type' => $entity->type->value,
            'amount' => $entity->amount,
            'paid_amount' => $entity->paid_amount,
            'returned_amount' => $entity->returned_amount,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];
    }
}
