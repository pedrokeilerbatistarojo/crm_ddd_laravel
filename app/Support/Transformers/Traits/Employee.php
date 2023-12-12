<?php

namespace Support\Transformers\Traits;

use League\Fractal\Resource\Item;
use Support\Transformers\EmployeesServiceEmployeeTransformer;

trait Employee
{
    /**
     * @param mixed $entity
     * @return Item|null
     */
    public function includeEmployee(mixed $entity): ?Item
    {
        return $entity->employee_id ? $this->item(
            (int)$entity->employee_id,
            app(EmployeesServiceEmployeeTransformer::class)
        ) : null;
    }
}
