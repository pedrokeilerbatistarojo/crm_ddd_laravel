<?php

namespace Domain\Clients\Transformers;

use Domain\Clients\Models\Client;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;
use Support\Transformers\LocalitiesServiceLocalityTransformer;
use Support\Transformers\Traits\CreatedByUser;
use Support\Transformers\Traits\LastModifiedByUser;

class ClientTransformer extends Transformer
{
    use CreatedByUser;
    use LastModifiedByUser;

    protected array $availableIncludes = [
        'createdByUser',
        'lastModifiedByUser',
        'locality',
        'clientNotes',
        'clientFiles',
    ];

    /**
     * @param Client $entity
     * @return array
     */
    public function transform(Client $entity): array
    {
        return [
            'id' => $entity->id,
            'email' => $entity->email,
            'document' => $entity->document,
            'name' => $entity->name,
            'phone' => $entity->phone,
            'birthdate' => $entity->birthdate,
            'address' => $entity->address,
            'postcode' => $entity->postcode,
            'locality_id' => $entity->locality_id,
            'opt_in' => $entity->opt_in,
            'lopd_agree' => $entity->lopd_agree,
            'created_by' => $entity->created_by,
            'last_modified_by' => $entity->last_modified_by,
            'created_at' => $entity->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $entity->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param Client $entity
     * @return Item|null
     */
    public function includeLocality(Client $entity): ?Item
    {
        return !empty($entity->locality_id) ? $this->item(
            (int)$entity->locality_id,
            app(LocalitiesServiceLocalityTransformer::class)
        ) : null;
    }

    /**
     * @param Client $entity
     * @return Collection|null
     */
    public function includeClientNotes(Client $entity): ?Collection
    {
        $clientNotes = $entity->clientNotes()->orderByDesc('id')->get();

        return $clientNotes ? $this->collection($clientNotes, app(ClientNoteTransformer::class)) : null;
    }

    /**
     * @param Client $entity
     * @return Collection|null
     */
    public function includeClientFiles(Client $entity): ?Collection
    {
        $clientFiles = $entity->clientFiles()->orderByDesc('id')->get();

        return $clientFiles ? $this->collection($clientFiles, app(ClientFileTransformer::class)) : null;
    }
}
