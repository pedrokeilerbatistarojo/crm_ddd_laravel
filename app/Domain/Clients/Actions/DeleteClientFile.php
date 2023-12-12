<?php

namespace Domain\Clients\Actions;

use Domain\Clients\Contracts\Repositories\ClientFilesRepository;
use Domain\Clients\Models\ClientFile;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteClientFile
{
    private ClientFilesRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param ClientFilesRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        ClientFilesRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return ClientFile
     * @throws ValidationException
     */
    public function __invoke(array $data): ClientFile
    {
        $validator = $this->validatorFactory->make($data, $this->rules());

        $validator->validate();

        return $this->repository->delete($data);
    }

    /**
     * @return array
     */
    private function rules(): array
    {
        return [
            'id' => 'required|exists:client_files,id'
        ];
    }
}
