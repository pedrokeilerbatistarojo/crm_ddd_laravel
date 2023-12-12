<?php

namespace Domain\Users\Transformers;

use Domain\Users\Models\User;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\CompaniesServiceCompanyTransformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;

class UserTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser',
        'company'
    ];

    /**
     * @param User $entity
     * @return array
     */
    public function transform(User $entity): array
    {
        return [
            'id' => $entity->id,
            'default_company_id' => $entity->default_company_id,
            'username' => $entity->username,
            'name' => $entity->name,
            'email' => $entity->email,
            'active' => $entity->active,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];
    }

    /**
     * @param User $entity
     * @return Item|null
     */
    public function includeCompany(User $entity): ?Item
    {
        return !empty($entity->default_company_id) ? $this->item(
            (int)$entity->default_company_id,
            app(CompaniesServiceCompanyTransformer::class)
        ) : null;
    }
}
