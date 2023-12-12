<?php

namespace Domain\CircuitReservations\Contracts\Services;

use Domain\CircuitReservations\DataTransferObjects\CircuitReservationEntity;
use Domain\CircuitReservations\DataTransferObjects\CircuitReservationSchedulesPdfResponse;
use Domain\CircuitReservations\DataTransferObjects\CircuitReservationSearchRequest;
use Domain\CircuitReservations\DataTransferObjects\CircuitReservationSearchResponse;
use Domain\CircuitReservations\DataTransferObjects\CircuitReservationSendUpcomingReservationEmailResponse;

interface CircuitReservationsService
{
    public function find(int $id, array $includes): ?CircuitReservationEntity;

    public function findByOrderDetail(int $id, array $includes): CircuitReservationSearchResponse;

    public function search(CircuitReservationSearchRequest $request): CircuitReservationSearchResponse;

    public function create(array $data): CircuitReservationEntity;

    public function sendUpcomingReservationEmail(int $id): CircuitReservationSendUpcomingReservationEmailResponse;

    public function markAsUsed(array $data): CircuitReservationEntity;

    public function update(array $data): CircuitReservationEntity;

    public function delete(array $data): CircuitReservationEntity;

    public function schedulesPdf(string $date): CircuitReservationSchedulesPdfResponse;

    public function sendEmail(array $data);
}
