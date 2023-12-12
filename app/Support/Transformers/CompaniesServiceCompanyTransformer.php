<?php

namespace Support\Transformers;

use Domain\Companies\Contracts\Services\CompaniesService;
use League\Fractal\TransformerAbstract as Transformer;

class CompaniesServiceCompanyTransformer extends Transformer
{
    /**
     * @param int $id
     * @return array
     */
    public function transform(int $id): array
    {
        $entity = app(CompaniesService::class)->find($id);

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
