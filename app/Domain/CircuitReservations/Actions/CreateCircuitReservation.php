<?php

namespace Domain\CircuitReservations\Actions;

use Domain\CircuitReservations\Contracts\Repositories\CircuitReservationOrderDetailsRepository;
use Domain\CircuitReservations\Contracts\Repositories\CircuitReservationsRepository;
use Domain\CircuitReservations\Models\CircuitReservation;
use Domain\Festives\Contracts\Services\FestivesService;
use Domain\Festives\DataTransferObjects\FestiveSearchRequest;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class CreateCircuitReservation
{
    /**
     * @param CircuitReservationsRepository $repository
     * @param CircuitReservationOrderDetailsRepository $circuitReservationOrderDetailsRepository
     * @param FestivesService $festivesService
     * @param Factory $validatorFactory
     */
    public function __construct(
        private readonly CircuitReservationsRepository $repository,
        private readonly CircuitReservationOrderDetailsRepository $circuitReservationOrderDetailsRepository,
        private readonly FestivesService $festivesService,
        private readonly Factory $validatorFactory
    ) {
    }

    /**
     * @param array $data
     * @return CircuitReservation
     * @throws ValidationException
     */
    public function __invoke(array $data): CircuitReservation
    {
        $validator = $this->validatorFactory->make($data, $this->rules($data));

        $validator->validate();

        $record = $this->repository->add($data);

        if (!empty($data['order_detail_id'])) {
            $this->circuitReservationOrderDetailsRepository->add([
                'id' => $record->id,
                'order_detail_id' => $data['order_detail_id']
            ]);
        }

        return $record;
    }

    /**
     * @param array $data
     * @return array
     */
    private function rules(array $data): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'order_detail_id' => 'nullable|exists:order_details,id',
            'date' => [
                'required',
                'date_format:Y-m-d',
                function ($attribute, $value, $fail) use ($data) {
                    $festiveRecords = $this->festivesService->search(
                        new FestiveSearchRequest([
                            'filters' => ['date' => $value],
                            'paginateSize' => config('system.infinite_pagination')
                        ])
                    )->getData();
                    $time = $data['time'];
                    if ($festiveRecords->count()) {
                        foreach ($festiveRecords as $festive) {
                            if (
                                $festive->type === 'Día Completo'
                                ||
                                in_array($time, $festive->closed_hours)
                            ) {
                                $fail('Date and time not available to reserve.');
                            }
                        }
                    }
                },
            ],
            'time' => 'required|date_format:H:i',
            'duration' => 'required|numeric',
            'adults' => 'required|numeric',
            'children' => 'required|numeric',
            'used' => 'required|boolean',
            'treatment_reservations' => 'nullable|numeric'
        ];
    }
}
