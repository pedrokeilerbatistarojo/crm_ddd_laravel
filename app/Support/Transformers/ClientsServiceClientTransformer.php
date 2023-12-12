<?php

namespace Support\Transformers;

use Domain\Clients\Contracts\Services\ClientsService;
use Domain\Clients\DataTransferObjects\ClientNoteSearchRequest;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as Transformer;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class ClientsServiceClientTransformer extends Transformer
{
    /**
     * @var array|string[]
     */
    protected array $availableIncludes = [
        'locality',
        'clientFiles',
        'clientNotes',
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
        $entity = app(ClientsService::class)->find($id);

        $this->entityData = [
            'id' => $entity->id,
            'email' => $entity->email,
            'document' => $entity->document,
            'name' => $entity->name,
            'phone' => $entity->phone,
            'birthdate' => $entity->birthdate,
            'address' => $entity->address,
            'postcode' => $entity->postcode,
            'opt_in' => $entity->opt_in,
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
    public function includeClientFiles(int $id): ?Collection
    {
        $payments = app(ClientsService::class)->searchFiles(
            new ClientNoteSearchRequest([
                'filters' => ['client_id' => $id],
                'includes' => [],
                'paginate_size' => config('system.infinite_pagination')
            ])
        );

        return $payments->getData()->count() ? $this->collection(
            $payments->getData()->pluck('id')->values()->toArray(),
            app(ClientsServiceClientFileTransformer::class)
        ) : null;
    }

    /**
     * @param int $id
     * @return Collection|null
     * @throws UnknownProperties
     */
    public function includeClientNotes(int $id): ?Collection
    {
        $payments = app(ClientsService::class)->searchNotes(
            new ClientNoteSearchRequest([
                'filters' => ['client_id' => $id],
                'includes' => [],
                'paginate_size' => config('system.infinite_pagination')
            ])
        );

        return $payments->getData()->count() ? $this->collection(
            $payments->getData()->pluck('id')->values()->toArray(),
            app(ClientsServiceClientNoteTransformer::class)
        ) : null;
    }

    /**
     * @param int $id
     * @return Item|null
     */
    public function includeLocality(int $id): ?Item
    {
        return !empty($this->entityData['locality_id']) ? $this->item(
            (int)$this->entityData['locality_id'],
            app(LocalitiesServiceLocalityTransformer::class)
        ) : null;
    }

}
