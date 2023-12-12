<?php

namespace Support\Transformers;

use Domain\Clients\Contracts\Services\ClientsService;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;

class ClientsServiceClientFileTransformer extends Transformer
{
    /**
     * @var array|string[]
     */
    protected array $availableIncludes = [
        'client',
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
        $entity = app(ClientsService::class)->findFile($id);

        $this->entityData = [
            'id' => $entity->id,
            'client_id' => $entity->client_id,
            'file' => $entity->file,
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
}
