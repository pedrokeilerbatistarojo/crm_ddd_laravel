<?php

namespace Domain\Employees\Transformers;

use Domain\Employees\Models\EmployeeOrder;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;

class EmployeeOrderTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser',
    ];

    /**
     * @param EmployeeOrder $entity
     * @return array
     */
    public function transform(EmployeeOrder $entity): array
    {
        return [
            'id' => $entity->id,
            'date' => $entity->date,
            'order' => $entity->order,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];
    }
}
