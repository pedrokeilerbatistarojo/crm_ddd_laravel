<?php

namespace Domain\CircuitReservations\Actions;

use Domain\CircuitReservations\Contracts\Repositories\CircuitReservationsRepository;
use Domain\CircuitReservations\Models\CircuitReservation;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class MarkAsUsedCircuitReservation
{
    private CircuitReservationsRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param CircuitReservationsRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        CircuitReservationsRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return CircuitReservation
     * @throws ValidationException
     */
    public function __invoke(array $data): CircuitReservation
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
            'id' => 'required|exists:circuit_reservations,id',
            'used' => 'required|boolean'
        ];
    }
}
