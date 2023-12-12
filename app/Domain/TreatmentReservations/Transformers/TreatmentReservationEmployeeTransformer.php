<?php

namespace Domain\TreatmentReservations\Transformers;

use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;
use League\Fractal\TransformerAbstract as Transformer;
use Domain\Employees\Contracts\Services\EmployeesService;

class TreatmentReservationEmployeeTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser'
    ];

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
            'updated_at' => $entity->updated_at
        ];
    }
}
