<?php

namespace Domain\Clients\Actions;

use Domain\Clients\Contracts\Repositories\ClientFilesRepository;
use Domain\Clients\Models\ClientFile;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Illuminate\Support\Str;

class CreateClientFile
{
    /**
     * @param ClientFilesRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        private readonly ClientFilesRepository $repository,
        private readonly Factory $validatorFactory
    ) {
    }

    /**
     * @param array $data
     * @return ClientFile
     * @throws ValidationException
     * @throws UnknownProperties
     */
    public function __invoke(array $data): ClientFile
    {
        $validator = $this->validatorFactory->make($data, $this->rules());

        $validator->validate();

        $file = $data['file'];
        $filename = str_replace([' ', ','], ['_', ''], Str::ascii($file->getClientOriginalName()));
        $filename = uniqId() . '_' . $filename;
        $file->move(storage_path('app/public/'), $filename);
        chdir(storage_path('app/public'));
            
        $data['file'] = $filename;

        if (!$record = $this->repository->add($data)) {
            throw new \RuntimeException('Client file can`t be saved.');
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
            'file' => 'required'
        ];
    }
}
