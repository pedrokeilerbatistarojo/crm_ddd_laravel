<?php

namespace Domain\Clients\Actions;

use Domain\Clients\Contracts\Repositories\ClientNotesRepository;
use Domain\Clients\Models\ClientNote;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class CreateClientNote
{
    /**
     * @param ClientNotesRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        private readonly ClientNotesRepository $repository,
        private readonly Factory $validatorFactory
    ) {
    }

    /**
     * @param array $data
     * @return ClientNote
     * @throws ValidationException
     * @throws UnknownProperties
     */
    public function __invoke(array $data): ClientNote
    {
        $validator = $this->validatorFactory->make($data, $this->rules());

        $validator->validate();

        if (!$record = $this->repository->add($data)) {
            throw new \RuntimeException('Client note can`t be saved.');
        }

        return $record;
    }

    /**
     * @return array
     */
    private function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'note' => 'required'
        ];
    }
}
