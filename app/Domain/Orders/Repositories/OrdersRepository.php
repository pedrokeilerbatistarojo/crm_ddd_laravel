<?php

namespace Domain\Orders\Repositories;

use Carbon\Carbon;
use Domain\Orders\Actions\CreateOrderDetail;
use Domain\Orders\Contracts\Repositories\OrdersRepository as RepositoryInterface;
use Domain\Orders\Models\Order;
use Domain\Payments\Actions\UpsertPayment;
use Domain\Payments\Contracts\Services\PaymentsService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Support\Core\Enums\SQLSort;
use Support\Exceptions\DatabaseException;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class OrdersRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var Order
     */
    private Order $entity;

    /**
     * @var OrderDetailsRepository
     */
    private OrderDetailsRepository $orderDetailsRepository;

    /**
     * @param Order $entity
     * @param OrderDetailsRepository $orderDetailsRepository
     */
    public function __construct(Order $entity, OrderDetailsRepository $orderDetailsRepository)
    {
        $this->entity = $entity;
        $this->orderDetailsRepository = $orderDetailsRepository;
    }

    //endregion

    //region RepositoryInterface">

    /**
     * @param array $data
     *
     * @return null|Order
     */
    public function add(array $data): ?Order
    {
        $entity = null;
        $qb = $this->getEntity()->query();

        DB::transaction(static function () use ($qb, &$entity, $data) {
            $entity = $qb->create($data);
            if (array_key_exists('payments', $data)) {
                $paymentsService = app(PaymentsService::class);
                foreach ($data['payments'] as $payment) {
                    $paymentsService->create([
                        'order_id' => $entity->id,
                        'due_date' => Carbon::now()->toDateTimeString(),
                        'paid_date' => Carbon::now()->toDateTimeString(),
                        ...$payment
                    ]);
                }
            }
            foreach ($data['details'] as $orderDetail) {
                app(CreateOrderDetail::class)([
                    'order_id' => $entity->id,
                    ...$orderDetail
                ]);
            }
        });

        return $entity;
    }

    /**
     * @return Order
     */
    public function getEntity(): Order
    {
        return $this->entity;
    }

    /**
     * @param Entity $entity
     *
     * @return void
     */
    public function setEntity(Entity $entity): void
    {
        $this->entity = $entity;
    }

    /**
     * @param array $data
     *
     * @return Order|null
     */
    public function delete(array $data): ?Order
    {
        $entity = $this->find($data['id']);

        if (!$entity) {
            return null;
        }

        $orderDetailsRepository = $this->orderDetailsRepository;

        DB::transaction(static function () use ($data, &$entity, $orderDetailsRepository) {
            $orderDetailsRepository->deleteWhere(['order_id' => $data['id']]);
            if (!$entity->delete()) {
                throw new DatabaseException('Sorry, we could not delete the the record from the database.');
            }
        });

        return $entity;
    }

    /**
     * @param array $filters
     * @param string $sortField
     * @param SQLSort $sortType
     * @return Collection
     */
    public function search(array $filters, string $sortField, SQLSort $sortType): Collection
    {
        return $this->searchQueryBuilder($filters, $sortField, $sortType)->get();
    }

    /**
     * @param array $filters
     * @param string $sortField
     * @param SQLSort $sortType
     * @return Builder
     */
    public function searchQueryBuilder(array $filters, string $sortField, SQLSort $sortType): Builder
    {
        $query = $this->getEntity()->newQuery()->select('orders.*');

        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $query->whereIn('id', (array)$filters['id']);
        }

        if (array_key_exists('ticket_number', $filters) && !empty($filters['ticket_number'])) {
            $query->whereIn('ticket_number', (array)$filters['ticket_number']);
        }

        if (array_key_exists('type', $filters) && !empty($filters['type'])) {
            $query->whereIn('type', (array)$filters['type']);
        }

        if (array_key_exists('sale_session_id', $filters) && !empty($filters['sale_session_id'])) {
            $query->whereIn('sale_session_id', (array)$filters['sale_session_id']);
        }

        if (array_key_exists('client_id', $filters) && !empty($filters['client_id'])) {
            $query->whereIn('client_id', (array)$filters['client_id']);
        }

        if (array_key_exists('client', $filters) && !empty($filters['client'])) {
            $query->join('clients', 'clients.id', '=', 'orders.client_id');
            $query->where('clients.name', 'like', '%' . $filters['client'] . '%');
        }

        if (array_key_exists('telephone_sale_seq', $filters) && !empty($filters['telephone_sale_seq'])) {
            $query->where('telephone_sale_seq', '=', $filters['telephone_sale_seq']);
        }

        if (array_key_exists('counter_sale_seq', $filters) && !empty($filters['counter_sale_seq'])) {
            $query->where('counter_sale_seq', 'like', '%' . $filters['counter_sale_seq'] . '%');
        }

        if (array_key_exists('company_id', $filters) && !empty($filters['company_id'])) {
            $query->whereIn('company_id', (array)$filters['company_id']);
        }

        if (array_key_exists('source', $filters) && !empty($filters['source'])) {
            $query->whereIn('source', (array)$filters['source']);
        }

        if (array_key_exists('locator', $filters) && !empty($filters['locator'])) {
            $query->where('locator', '=', $filters['locator']);
        }

        if (array_key_exists('total_price', $filters) && !empty($filters['total_price'])) {
            $query->where('total_price', '=', $filters['total_price']);
        }

        if (array_key_exists('created_by', $filters) && !empty($filters['created_by'])) {
            $query->whereIn('created_by', (array)$filters['created_by']);
        }

        if (array_key_exists('last_modified_by', $filters) && !empty($filters['last_modified_by'])) {
            $query->whereIn('last_modified_by', (array)$filters['last_modified_by']);
        }

        if (array_key_exists('created_at_from', $filters) && !empty($filters['created_at_from'])) {
            $query->where('created_at', '>=', $filters['created_at_from']);
        }

        if (array_key_exists('created_at_to', $filters) && !empty($filters['created_at_to'])) {
            $query->where('created_at', '<=', $filters['created_at_to']);
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }

    //endregion
}
