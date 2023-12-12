<?php

namespace Domain\TreatmentScheduleNotes\Contracts\Services;

use Domain\TreatmentScheduleNotes\DataTransferObjects\TreatmentScheduleNoteEntity;
use Domain\TreatmentScheduleNotes\DataTransferObjects\TreatmentScheduleNoteSearchRequest;
use Domain\TreatmentScheduleNotes\DataTransferObjects\TreatmentScheduleNoteResponse;

interface TreatmentScheduleNotesService
{
    /**
     * @param array $data
     * @return TreatmentScheduleNoteEntity|null
     */
    public function create(array $data): ?TreatmentScheduleNoteEntity;

    /**
     * @param array $data
     * @return TreatmentScheduleNoteEntity|null
     */
    public function delete(array $data): ?TreatmentScheduleNoteEntity;

    /**
     * @param int $id
     * @param array $includes
     * @return TreatmentScheduleNoteEntity|null
     */
    public function find(int $id, array $includes = []): ?TreatmentScheduleNoteEntity;

    /**
     * @param TreatmentScheduleNoteSearchRequest $request
     * @return TreatmentScheduleNoteResponse
     */
    public function search(TreatmentScheduleNoteSearchRequest $request): TreatmentScheduleNoteResponse;

    /**
     * @param array $data
     * @return TreatmentScheduleNoteEntity|null
     */
    public function update(array $data): ?TreatmentScheduleNoteEntity;
}
