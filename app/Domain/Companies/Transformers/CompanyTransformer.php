<?php

namespace Domain\Companies\Transformers;

use Domain\Companies\Models\Company;
use League\Fractal\TransformerAbstract as Transformer;

class CompanyTransformer extends Transformer
{
    protected array $availableIncludes = [
    ];

    /**
     * @param Company $entity
     * @return array
     */
    public function transform(Company $entity): array
    {
        return [
            'id' => $entity->id,
            'name' => $entity->name,
            'cif' => $entity->cif,
            'email' => $entity->email,
            'phone' => $entity->phone,
            'address' => $entity->address,
            'zip_code' => $entity->zip_code,
            'locality' => $entity->locality,
            'province' => $entity->province,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];
    }
}
