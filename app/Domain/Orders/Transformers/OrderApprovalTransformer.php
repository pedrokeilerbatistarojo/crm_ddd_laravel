<?php

namespace Domain\Orders\Transformers;

use Domain\Orders\Models\OrderApproval;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;

class OrderApprovalTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser',
    ];

    /**
     * @param OrderApproval $entity
     * @return array
     */
    public function transform(OrderApproval $entity): array
    {
        return [
            'id' => $entity->id,
            'locator' => $entity->locator,
            'order_data' => $entity->order_data,
            'is_duplicated' => $entity->is_duplicated,
            'is_reservation' => $entity->is_reservation,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];
    }

}
