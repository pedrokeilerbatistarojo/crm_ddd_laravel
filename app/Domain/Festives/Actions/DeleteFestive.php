<?php

namespace Domain\Festives\Actions;

use Domain\Festives\Contracts\Repositories\FestivesRepository;
use Domain\Festives\Models\Festive;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteFestive
{
    private FestivesRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param FestivesRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        FestivesRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return Festive
     * @throws ValidationException
     */
    public function __invoke(array $data): Festive
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
            'id' => 'required|exists:festives'
        ];
    }
}
