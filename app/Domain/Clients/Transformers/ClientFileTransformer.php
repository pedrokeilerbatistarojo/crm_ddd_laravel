<?php

namespace Domain\Clients\Transformers;

use Domain\Clients\Models\ClientFile;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;

class ClientFileTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser',
        'client',
    ];

    /**
     * @param ClientFile $entity
     * @return array
     */
    public function transform(ClientFile $entity): array
    {
        return [
            'id' => $entity->id,
            'client_id' => $entity->client_id,
            'file' => $entity->file,
            'description' => $entity->description,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $entity->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param ClientFile $entity
     * @return Item|null
     */
    public function includeClient(ClientFile $entity): ?Item
    {
        $client = $entity->client;

        return $client ? $this->item($client, app(ClientTransformer::class)) : null;
    }
}
