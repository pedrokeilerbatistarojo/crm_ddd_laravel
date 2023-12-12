<?php

namespace Domain\TreatmentScheduleNotes\Actions;

use Domain\TreatmentScheduleNotes\Contracts\Repositories\TreatmentScheduleNotesRepository;
use Domain\TreatmentScheduleNotes\Models\TreatmentScheduleNote;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteTreatmentScheduleNote
{
    private TreatmentScheduleNotesRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param TreatmentScheduleNotesRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        TreatmentScheduleNotesRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return TreatmentScheduleNote
     * @throws ValidationException
     */
    public function __invoke(array $data): TreatmentScheduleNote
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
            'id' => 'required|exists:treatment_schedule_notes'
        ];
    }
}
