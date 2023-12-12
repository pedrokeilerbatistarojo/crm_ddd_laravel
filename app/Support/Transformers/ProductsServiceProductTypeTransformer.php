<?php

namespace Support\Transformers;

use Domain\Products\Contracts\Services\ProductsService;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;

class ProductsServiceProductTypeTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser',
        'category'
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
        $entity = app(ProductsService::class)->findProductType($id);

        $this->entityData = [
            'id' => $entity->id,
            'category_id' => $entity->category_id,
            'name' => $entity->name,
            'background_color' => $entity->background_color,
            'text_color' => $entity->text_color,
            'priority' => $entity->priority,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'update_at' => $entity->updated_at
        ];

        return $this->entityData;
    }

    /**
     * @return Item|null
     */
    public function includeCategory(): ?Item
    {
        return !empty($this->entityData['category_id']) ? $this->item(
            (int)$this->entityData['category_id'],
            app(ProductsServiceCategoryTransformer::class)
        ) : null;
    }
}
