<?php

namespace Support\Transformers;

use Domain\Users\Contracts\Services\UsersService;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;

class UsersServiceUserTransformer extends Transformer
{
    /**
     * @var array|string[]
     */
    protected array $availableIncludes = [
        'company'
    ];

    /**
     * @var array
     */
    protected array $entityData = [];

    /**
     * @param int $id
     * @return array
     */
    public function transform(int $id): array
    {
        $entity = app(UsersService::class)->find($id);

        $this->entityData = [
            'id' => $entity->id,
            'username' => $entity->username,
            'name' => $entity->name,
            'email' => $entity->email,
            'default_company_id' => $entity->default_company_id,
            'active' => $entity->active,
        ];

        return $this->entityData;
    }

    /**
     * @return Item|null
     */
    public function includeCompany(): ?Item
    {
        return !empty($this->entityData['default_company_id']) ? $this->item(
            (int)$this->entityData['default_company_id'],
            app(CompaniesServiceCompanyTransformer::class)
        ) : null;
    }
}
