<?php

namespace Domain\Employees\Transformers;

use Domain\Employees\Models\Employee;
use Domain\Employees\Models\EmployeeTimeOff;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;
use League\Fractal\Resource\Item;

class EmployeeTimeOffTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser',
        'employee'
    ];

    /**
     * @param EmployeeTimeOff $entity
     * @return array
     */
    public function transform(EmployeeTimeOff $entity): array
    {
        return [
            'id' => $entity->id,
            'employee_id' => $entity->employee_id,
            'type' => $entity->type,
            'from_date' => $entity->from_date,
            'to_date' => $entity->to_date,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];
    }

    /**
     * @param EmployeeTimeOff $entity
     * @return Item|null
     */
    public function includeEmployee(EmployeeTimeOff $entity): ?Item
    {
        $employee = $entity->employee;

        return $employee ? $this->item($employee, app(EmployeeTransformer::class)) : null;
    }
}
