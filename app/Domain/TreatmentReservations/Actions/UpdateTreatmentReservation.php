<?php

namespace Domain\TreatmentReservations\Actions;

use Domain\Festives\Contracts\Services\FestivesService;
use Domain\Festives\DataTransferObjects\FestiveSearchRequest;
use Domain\TreatmentReservations\Contracts\Repositories\TreatmentReservationOrderDetailsRepository;
use Domain\TreatmentReservations\Contracts\Repositories\TreatmentReservationsRepository;
use Domain\TreatmentReservations\Models\TreatmentReservation;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class UpdateTreatmentReservation
{
    /**
     * @param TreatmentReservationsRepository $repository
     * @param TreatmentReservationOrderDetailsRepository $treatmentReservationOrderDetailsRepository
     * @param FestivesService $festivesService
     * @param Factory $validatorFactory
     */
    public function __construct(
        private readonly TreatmentReservationsRepository $repository,
        private readonly TreatmentReservationOrderDetailsRepository $treatmentReservationOrderDetailsRepository,
        private readonly FestivesService $festivesService,
        private readonly Factory $validatorFactory
    ) {
    }

    /**
     * @param array $data
     * @return TreatmentReservation
     * @throws ValidationException
     */
    public function __invoke(array $data): TreatmentReservation
    {
        $validator = $this->validatorFactory->make($data, $this->rules($data));

        $validator->validate();

        $record = $this->repository->edit($data);

        if (!empty($data['order_detail_id'])) {
            $this->treatmentReservationOrderDetailsRepository->firstOrCreate([
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
            'id' => 'required|exists:treatment_reservations,id',
            'order_detail_id' => 'nullable|exists:order_details,id',
            'client_id' => 'required|exists:clients,id',
            'employee_id' => 'nullable|exists:employees,id',
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
            'used' => 'required|boolean'
        ];
    }
}
