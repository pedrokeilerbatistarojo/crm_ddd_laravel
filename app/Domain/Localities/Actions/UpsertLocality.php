<?php

namespace Domain\Localities\Actions;

use Domain\Localities\Contracts\Repositories\LocalitiesRepository;
use Domain\Localities\Models\Locality;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpsertLocality
{
    private LocalitiesRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param LocalitiesRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        LocalitiesRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return Locality
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function __invoke(array $data): Locality
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
            'zip_code' => 'required|string|max:5',
            'municipio_id' => 'required|string|max:5',
            'locality' => 'required',
            'population_unit_code' => 'required|string|max:7',
            'singular_entity_name' => 'required',
            'population' => 'required',
            'province_id' => 'required',
        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:localities,id';
        }

        return $rules;
    }
}
