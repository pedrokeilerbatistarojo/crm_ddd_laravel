<?php

namespace Domain\SaleSessions\Transformers;

use Domain\Orders\Contracts\Services\OrdersService;
use Domain\Orders\DataTransferObjects\OrderSearchRequest;
use Domain\SaleSessions\Models\SaleSession;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Transformers\OrdersServiceOrderTransformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\Employee;
use Support\Transformers\Traits\LastModifiedByUser;
use Support\Transformers\UsersServiceUserTransformer;

class SaleSessionTransformer extends Transformer
{
    use CreatedByUser;
    use Employee;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'createdByUser',
        'closedByUser',
        'lastModifiedByUser',
        'employee',
        'orders'
    ];

    /**
     * @param SaleSession $entity
     * @return array
     */
    public function transform(SaleSession $entity): array
    {
        return [
            'id' => $entity->id,
            'employee_id' => $entity->employee_id,
            'session_status' => $entity->session_status->value,
            'session_type' => $entity->session_type->value,
            'start_date' => $entity->start_date,
            'end_date' => $entity->end_date,
            'start_amount' => $entity->start_amount,
            'end_amount' => $entity->end_amount,
            'created_by' => $entity->created_by,
            'closed_by' => $entity->closed_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];
    }

    /**
     * @param mixed $entity
     * @return Item|null
     */
    public function includeClosedByUser(mixed $entity): ?Item
    {
        return $entity->closed_by ? $this->item(
            (int)$entity->closed_by,
            app(UsersServiceUserTransformer::class)
        ) : null;
    }

    /**
     * @param SaleSession $entity
     * @return Collection|null
     * @throws UnknownProperties
     */
    public function includeOrders(SaleSession $entity): ?Collection
    {
        $orders = app(OrdersService::class)->search(
            app(OrderSearchRequest::class, [
                'args' => [
                    'filters' => ['sale_session_id' => $entity->id],
                    'includes' => [],
                    'paginateSize' => config('system.infinite_pagination')
                ]
            ])
        );

        return $orders->getData()->count() ? $this->collection(
            $orders->getData()->pluck('id')->values()->toArray(),
            app(OrdersServiceOrderTransformer::class)
        ) : null;
    }
}
