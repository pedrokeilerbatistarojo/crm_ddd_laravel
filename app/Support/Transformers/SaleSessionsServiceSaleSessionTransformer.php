<?php

namespace Support\Transformers;

use Domain\SaleSessions\Contracts\Services\SaleSessionsService;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\Employee;
use Support\Transformers\Traits\LastModifiedByUser;

class SaleSessionsServiceSaleSessionTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;

    /**
     * @var array|string[]
     */
    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser',
        'employee'
    ];

    /**
     * @var array
     */
    protected array $entityData = [];

    /**
     * @param int $id
     * @return Item|null
     */
    public function includeEmployee(int $id): ?Item
    {
        return !empty($this->entityData['employee_id']) ? $this->item(
            (int)$this->entityData['employee_id'],
            app(EmployeesServiceEmployeeTransformer::class)
        ) : null;
    }

    /**
     * @param int $id
     * @return array
     */
    public function transform(int $id): array
    {
        $entity = app(SaleSessionsService::class)->find($id);

        $this->entityData = [
            'id' => $entity->id,
            'employee_id' => $entity->employee_id,
            'session_status' => $entity->session_status,
            'session_type' => $entity->session_type,
            'start_date' => $entity->start_date,
            'end_date' => $entity->end_date,
            'start_amount' => $entity->start_amount,
            'end_amount' => $entity->end_amount,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'update_at' => $entity->updated_at
        ];

        return $this->entityData;
    }
}
