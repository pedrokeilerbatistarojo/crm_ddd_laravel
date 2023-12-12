<?php

namespace Domain\TreatmentReservations\Contracts\Services;

use Domain\Employees\Models\Employee;
use Domain\TreatmentReservations\DataTransferObjects\TreatmentReservationEntity;
use Domain\TreatmentReservations\DataTransferObjects\TreatmentReservationSearchRequest;
use Domain\TreatmentReservations\DataTransferObjects\TreatmentReservationSearchResponse;
use Domain\TreatmentReservations\DataTransferObjects\TreatmentReservationSendUpcomingReservationEmailResponse;
use Symfony\Component\HttpFoundation\Request;

interface TreatmentReservationsService
{
    public function find(int $id, array $includes = []): ?TreatmentReservationEntity;

    public function findByOrderDetail(int $id, array $includes = []): TreatmentReservationSearchResponse;

    public function search(TreatmentReservationSearchRequest $request): TreatmentReservationSearchResponse;

    public function create(array $data): TreatmentReservationEntity;

    public function sendUpcomingReservationEmail(int $id): TreatmentReservationSendUpcomingReservationEmailResponse;

    public function markAsUsed(array $data): TreatmentReservationEntity;

    public function update(array $data): TreatmentReservationEntity;

    public function delete(array $data): TreatmentReservationEntity;

    public function schedulesPdf(string $date, Request $request);

    public function schedulesEmployeePdf(string $date, Employee $employee, Request $request);

    public function sendEmail(array $data);

}
