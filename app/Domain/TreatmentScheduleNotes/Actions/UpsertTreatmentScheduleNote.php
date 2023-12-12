<?php

namespace Domain\TreatmentScheduleNotes\Actions;

use Domain\TreatmentScheduleNotes\Contracts\Repositories\TreatmentScheduleNotesRepository;
use Domain\TreatmentScheduleNotes\Models\TreatmentScheduleNote;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpsertTreatmentScheduleNote
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
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function __invoke(array $data): TreatmentScheduleNote
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
            'date' => 'required|date_format:Y-m-d|date',
            'employee_id' => 'nullable',
            'from_hour' => 'required',
            'to_hour' => 'required',
            'note' => 'required'
        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:treatment_schedule_notes';
        }

        return $rules;
    }
}
