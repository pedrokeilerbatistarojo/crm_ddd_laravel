<?php

namespace Support\Transformers;

use Domain\Invoices\Contracts\Services\InvoicesService;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;

class InvoicesServiceInvoiceTransformer extends Transformer
{
    /**
     * @var array
     */
    protected array $availableIncludes = [
        'client'
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
        $entity = app(InvoicesService::class)->find($id);

        $this->entityData = [
            'id' => $entity->id,
            'client_id' => $entity->client_id,
            'number' => $entity->number,
            'description' => $entity->description,
            'invoice_type' => $entity->invoice_type,
            'invoice_type_id' => $entity->invoice_type_id,
            'invoice_date' => $entity->invoice_date,
            'address' => $entity->address,
            'zip_code' => $entity->zip_code,
            'locality' => $entity->locality,
            'province' => $entity->province,
            'observations' => $entity->observations,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
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
}
