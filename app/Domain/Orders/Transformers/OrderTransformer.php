<?php

namespace Domain\Orders\Transformers;

use Domain\Orders\Models\Order;
use Domain\Payments\Contracts\Services\PaymentsService;
use Domain\Payments\DataTransferObjects\PaymentSearchRequest;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Transformers\CompaniesServiceCompanyTransformer;
use Support\Transformers\PaymentsServicePaymentTransformer;
use Support\Transformers\SaleSessionsServiceSaleSessionTransformer;
use Support\Transformers\Traits\Client;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;

class OrderTransformer extends Transformer
{
    use CreatedByUser;
    use Client;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'client',
        'createdByUser',
        'company',
        'lastModifiedByUser',
        'orderDetails',
        'payments',
        'saleSession'
    ];

    /**
     * @param Order $entity
     * @return array
     */
    public function transform(Order $entity): array
    {
        return [
            'id' => $entity->id,
            'sale_session_id' => $entity->sale_session_id,
            'locator' => $entity->locator,
            'client_id' => $entity->client_id,
            'company_id' => $entity->company_id,
            'source' => $entity->source->value,
            'discount' => $entity->discount,
            'total_price' => $entity->total_price,
            'ticket_number' => $entity->ticket_number,
            'type' => $entity->type->value,
            'telephone_sale_seq' => $entity->telephone_sale_seq,
            'counter_sale_seq' => $entity->counter_sale_seq,
            'used_purchase' => $entity->used_purchase,
            'note' => $entity->note,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ];
    }

    /**
     * @param Order $entity
     * @return Item|null
     */
    public function includeCompany(Order $entity): ?Item
    {
        return $entity->company_id ? $this->item(
            (int)$entity->company_id,
            app(CompaniesServiceCompanyTransformer::class)
        ) : null;
    }

    /**
     * @param Order $entity
     * @return Collection|null
     */
    public function includeOrderDetails(Order $entity): ?Collection
    {
        $orderDetails = $entity->orderDetails;

        return $orderDetails ? $this->collection($orderDetails, app(OrderDetailTransformer::class)) : null;
    }

    /**
     * @param Order $entity
     * @return Collection|null
     * @throws UnknownProperties
     */
    public function includePayments(Order $entity): ?Collection
    {
        $payments = app(PaymentsService::class)->search(
            new PaymentSearchRequest([
                'filters' => ['order_id' => $entity->id],
                'includes' => [],
                'paginate_size' => config('system.infinite_pagination')
            ])
        );

        return $payments->getData()->count() ? $this->collection(
            $payments->getData()->pluck('id')->values()->toArray(),
            app(PaymentsServicePaymentTransformer::class)
        ) : null;
    }

    /**
     * @param Order $entity
     * @return Item|null
     */
    public function includeSaleSession(Order $entity): ?Item
    {
        return $entity->sale_session_id ? $this->item(
            (int)$entity->sale_session_id,
            app(SaleSessionsServiceSaleSessionTransformer::class)
        ) : null;
    }
}
