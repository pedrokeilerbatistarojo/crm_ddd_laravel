<?php

namespace Domain\TreatmentReservations\Actions;

use Domain\TreatmentReservations\Contracts\Repositories\TreatmentReservationsRepository;
use Domain\TreatmentReservations\Models\TreatmentReservation;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class MarkAsUsedTreatmentReservation
{
    private TreatmentReservationsRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param TreatmentReservationsRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        TreatmentReservationsRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return TreatmentReservation
     * @throws ValidationException
     */
    public function __invoke(array $data): TreatmentReservation
    {
        $validator = $this->validatorFactory->make($data, $this->rules());

        $validator->validate();

        return $this->repository->edit(
            Arr::only($data, ['id', 'used'])
        );
    }

    /**
     * @return array
     */
    private function rules(): array
    {
        return [
            'id' => 'required|exists:treatment_reservations,id',
            'used' => 'required|boolean'
        ];
    }
}
