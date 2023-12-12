<?php

namespace Domain\Products\Transformers;

use Domain\Products\Models\ProductType;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;
use League\Fractal\Resource\Item;

class ProductTypeTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser',
        'category'
    ];

    /**
     * @param ProductType $entity
     * @return array
     */
    public function transform(ProductType $entity): array
    {
        return [
            'id' => $entity->id,
            'category_id' => $entity->category_id,
            'name' => $entity->name,
            'background_color' => $entity->background_color,
            'text_color' => $entity->text_color,
            'priority' => $entity->priority,
            'active' => $entity->active,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];
    }

    /**
     * @param ProductType $entity
     * @return Item|null
     */
    public function includeCategory(ProductType $entity): ?Item
    {
        $category = $entity->category;

        return $category ? $this->item($category, app(CategoryTransformer::class)) : null;
    }
}
