<?php

namespace Domain\Products\Transformers;

use Domain\Products\Models\Product;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;

class ProductTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser',
        'productDiscounts',
        'productType',
    ];

    /**
     * @param Product $entity
     * @return array
     */
    public function transform(Product $entity): array
    {
        return [
            'id' => $entity->id,
            'product_type_id' => $entity->product_type_id,
            'image' => $entity->image,
            'name' => $entity->name,
            'short_description' => $entity->short_description,
            'description' => $entity->description,
            'price' => $entity->price,
            'price_type' => $entity->price_type->value,
            'circuit_sessions' => $entity->circuit_sessions,
            'treatment_sessions' => $entity->treatment_sessions,
            'online_sale' => $entity->online_sale,
            'editable' => $entity->editable,
            'available' => $entity->available,
            'background_color' => $entity->background_color,
            'text_color' => $entity->text_color,
            'priority' => $entity->priority,
            'active' => $entity->active,
            'all_reserves_on_same_day' => $entity->all_reserves_on_same_day,
            'duration_treatment_schedule' => $entity->duration_treatment_schedule,
            'duration_circuit_schedule' => $entity->duration_circuit_schedule,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];
    }

    /**
     * @param Product $entity
     * @return Collection|null
     */
    public function includeProductDiscounts(Product $entity): ?Collection
    {
        $productDiscounts = $entity->discounts;

        return $productDiscounts ? $this->collection($productDiscounts, app(ProductDiscountTransformer::class)) : null;
    }

    /**
     * @param Product $entity
     * @return Item|null
     */
    public function includeProductType(Product $entity): ?Item
    {
        $productType = $entity->productType;

        return $productType ? $this->item($productType, app(ProductTypeTransformer::class)) : null;
    }
}
