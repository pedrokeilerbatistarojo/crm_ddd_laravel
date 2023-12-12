<?php

namespace Support\Transformers;

use Domain\Employees\Contracts\Services\EmployeesService;
use League\Fractal\TransformerAbstract as Transformer;

class EmployeesServiceEmployeeTransformer extends Transformer
{
    protected array $availableIncludes = [];

    /**
     * @param int $id
     * @return array
     */
    public function transform(int $id): array
    {
        $entity = app(EmployeesService::class)->find($id);

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
            'update_at' => $entity->updated_at
        ];
    }
}
