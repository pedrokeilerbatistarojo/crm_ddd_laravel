<?php

namespace Domain\Clients\Actions;

use Domain\Clients\Contracts\Repositories\ClientsRepository;
use Domain\Clients\Models\Client;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteClient
{
    private ClientsRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param ClientsRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        ClientsRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return Client
     * @throws ValidationException
     */
    public function __invoke(array $data): Client
    {
        $validator = $this->validatorFactory->make($data, $this->rules($data));

        $validator->validate();

        return $this->repository->delete($data);
    }

    /**
     * @param $data
     * @return array
     */
    private function rules($data): array
    {
        return [
            'id' => 'required|exists:clients'
        ];
    }
}
