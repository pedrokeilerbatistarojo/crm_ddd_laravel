<?php

namespace Support\Transformers;

use Domain\Payments\DataTransferObjects\PaymentSearchRequest;
use Domain\Products\Contracts\Services\ProductsService;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;

class ProductsServiceProductTransformer extends Transformer
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
     * @var array
     */
    protected array $entityData = [];

    /**
     * @param int $id
     * @return array
     */
    public function transform(int $id): array
    {
        $entity = app(ProductsService::class)->find($id);

        $this->entityData = [
            'id' => $entity->id,
            'product_type_id' => $entity->product_type_id,
            'image' => $entity->image,
            'name' => $entity->name,
            'short_description' => $entity->short_description,
            'description' => $entity->description,
            'price' => $entity->price,
            'price_type' => $entity->price_type,
            'circuit_sessions' => $entity->circuit_sessions,
            'treatment_sessions' => $entity->treatment_sessions,
            'online_sale' => $entity->online_sale,
            'editable' => $entity->editable,
            'available' => $entity->available,
            'priority' => $entity->priority,
            'all_reserves_on_same_day' => $entity->all_reserves_on_same_day,
            'duration_treatment_schedule' => $entity->duration_treatment_schedule,
            'duration_circuit_schedule' => $entity->duration_circuit_schedule,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'update_at' => $entity->updated_at
        ];

        return $this->entityData;
    }

    /**
     * @param int $id
     * @return Collection|null
     * @throws UnknownProperties
     */
    public function includeProductDiscounts(int $id): ?Collection
    {
        $records = app(ProductsService::class)->searchProductDiscounts(
            new PaymentSearchRequest([
                'filters' => ['product_id' => $id],
                'includes' => [],
                'paginate_size' => config('system.infinite_pagination')
            ])
        );

        return $records->getData()->count() ? $this->collection(
            $records->getData()->pluck('id')->values()->toArray(),
            app(ProductsServiceProductDiscountTransformer::class)
        ) : null;
    }

    /**
     * @return Item|null
     */
    public function includeProductType(): ?Item
    {
        return !empty($this->entityData['product_type_id']) ? $this->item(
            (int)$this->entityData['product_type_id'],
            app(ProductsServiceProductTypeTransformer::class)
        ) : null;
    }
}
