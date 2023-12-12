<?php

namespace Domain\Clients\Actions;

use Domain\Clients\Contracts\Repositories\ClientsRepository;
use Domain\Clients\Models\Client;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpsertClient
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
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function __invoke(array $data): Client
    {
        $validator = $this->validatorFactory->make($data, $this->rules($data));

        $validator->validate();

        $method = array_key_exists('id', $data) ? 'edit' : 'add';

        return $this->repository->$method($data);
    }

    /**
     * @param $data
     * @return array
     */
    private function rules($data): array
    {
        $rules = [
            'email' => 'nullable',
            'document' => 'nullable|unique:clients,document',
            'name' => 'required',
            'birthdate' => 'nullable|date',
            'opt_in' => 'required|boolean'
        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:clients';
            $rules['document'] .= ',' . $data['id'] . ',id';
        }

        return $rules;
    }
}
