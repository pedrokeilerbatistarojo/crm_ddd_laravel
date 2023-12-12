<?php

namespace Domain\TreatmentScheduleNotes\Transformers;

use Domain\Employees\Transformers\EmployeeTransformer;
use Domain\TreatmentScheduleNotes\Models\TreatmentScheduleNote;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;

class TreatmentScheduleNoteTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser'
    ];

    /**
     * @param TreatmentScheduleNote $entity
     * @return array
     */
    public function transform(TreatmentScheduleNote $entity): array
    {
        return [
            'id' => $entity->id,
            'employee_id' => $entity->employee_id,
            'date' => $entity->date->format('Y-m-d'),
            'note' => $entity->note,
            'from_hour' => $entity->from_hour,
            'to_hour' => $entity->to_hour,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];
    }

    /**
     * @param TreatmentScheduleNote $entity
     * @return Item|null
     */
    public function includeEmployee(TreatmentScheduleNote $entity): ?Item
    {
        $employee = $entity->employee;

        return $employee ? $this->item($employee, app(EmployeeTransformer::class)) : null;
    }

}
