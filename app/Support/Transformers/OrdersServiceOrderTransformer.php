<?php

namespace Support\Transformers;

use Domain\Orders\Contracts\Services\OrdersService;
use Domain\Payments\Contracts\Services\PaymentsService;
use Domain\Payments\DataTransferObjects\PaymentSearchRequest;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class OrdersServiceOrderTransformer extends Transformer
{
    /**
     * @var array
     */
    protected array $availableIncludes = [
        'client',
        'payments'
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
        $entity = app(OrdersService::class)->find($id);

        $this->entityData = [
            'locator' => $entity->locator,
            'client_id' => $entity->client_id,
            'source' => $entity->source,
            'discount' => $entity->discount,
            'total_price' => $entity->total_price,
            'ticket_number' => $entity->ticket_number,
            'type' => $entity->type,
            'telephone_sale_seq' => $entity->telephone_sale_seq,
            'counter_sale_seq' => $entity->counter_sale_seq,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'update_at' => $entity->updated_at
        ];

        return $this->entityData;
    }

    /**
     * @param int $id
     * @return Item|null
     */
    public function includeClient(int $id): ?Item
    {
        return !empty($this->entityData['client_id']) ? $this->item(
            (int)$this->entityData['client_id'],
            app(ClientsServiceClientTransformer::class)
        ) : null;
    }

    /**
     * @param int $id
     * @return Collection|null
     * @throws UnknownProperties
     */
    public function includePayments(int $id): ?Collection
    {
        $payments = app(PaymentsService::class)->search(
            new PaymentSearchRequest([
                'filters' => ['order_id' => $id],
                'includes' => [],
                'paginate_size' => config('system.infinite_pagination')
            ])
        );

        return $payments->getData()->count() ? $this->collection(
            $payments->getData()->pluck('id')->values()->toArray(),
            app(PaymentsServicePaymentTransformer::class)
        ) : null;
    }
}
