<?php

namespace Domain\Invoices\Transformers;

use Domain\Invoices\Models\Invoice;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;
use Support\Transformers\Traits\Client;

class InvoiceTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;
    use Client;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser',
        'client'
    ];

    /**
     * @param Invoice $entity
     * @return array
     */
    public function transform(Invoice $entity): array
    {
        return [
            'id' => $entity->id,
            'client_id' => $entity->client_id,
            'number' => $entity->number,
            'description' => $entity->description,
            'invoice_type' => $entity->invoice_type->value,
            'invoice_type_id' => $entity->invoice_type_id,
            'invoice_date' => $entity->invoice_date->toDateString(),
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
    }
}
