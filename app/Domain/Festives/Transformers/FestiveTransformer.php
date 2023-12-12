<?php

namespace Domain\Festives\Transformers;

use Domain\Festives\Models\Festive;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;

class FestiveTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser'
    ];

    /**
     * @param Festive $entity
     * @return array
     */
    public function transform(Festive $entity): array
    {
        return [
            'id' => $entity->id,
            'date' => $entity->date->format('Y-m-d'),
            'description' => $entity->description,
            'type' => $entity->type,
            'closed_hours' => $entity->closed_hours,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];
    }
}
