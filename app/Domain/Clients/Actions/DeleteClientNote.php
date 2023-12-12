<?php

namespace Domain\Clients\Actions;

use Domain\Clients\Contracts\Repositories\ClientNotesRepository;
use Domain\Clients\Models\ClientNote;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteClientNote
{
    private ClientNotesRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param ClientNotesRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        ClientNotesRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return ClientNote
     * @throws ValidationException
     */
    public function __invoke(array $data): ClientNote
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
            'id' => 'required|exists:client_notes,id'
        ];
    }
}
