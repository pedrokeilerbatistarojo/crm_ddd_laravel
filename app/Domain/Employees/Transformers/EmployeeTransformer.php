<?php

namespace Domain\Employees\Transformers;

use Domain\Employees\Models\Employee;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;

class EmployeeTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser'
    ];

    /**
     * @param Employee $entity
     * @return array
     */
    public function transform(Employee $entity): array
    {
        return [
            'id' => $entity->id,
            'email' => $entity->email,
            'first_name' => $entity->first_name,
            'last_name' => $entity->last_name,
            'second_last_name' => $entity->second_last_name,
            'phone' => $entity->phone,
            'active' => $entity->active,
            'is_specialist' => $entity->is_specialist,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];
    }
}
