<?php

namespace Domain\Orders\Contracts\Services;

use Domain\Orders\DataTransferObjects\OrderApprovalEntitiesCollection;
use Domain\Orders\DataTransferObjects\OrderApprovalEntity;
use Domain\Orders\DataTransferObjects\OrderApprovalSearchRequest;
use Domain\Orders\DataTransferObjects\OrderApprovalSearchResponse;
use Domain\Orders\DataTransferObjects\OrderDetailEntity;
use Domain\Orders\DataTransferObjects\OrderDetailSearchRequest;
use Domain\Orders\DataTransferObjects\OrderDetailSearchResponse;
use Domain\Orders\DataTransferObjects\OrderEntity;
use Domain\Orders\DataTransferObjects\OrderProductionReportPDFResponse;
use Domain\Orders\DataTransferObjects\OrderSearchRequest;
use Domain\Orders\DataTransferObjects\OrderSearchResponse;
use Domain\Orders\DataTransferObjects\OrderSendTicketEmailResponse;
use Domain\Orders\DataTransferObjects\OrderTicketPDFResponse;

interface OrdersService
{
    public function create(array $data): OrderEntity;

    public function createDetail(array $data): OrderDetailEntity;

    public function delete(array $data): OrderEntity;

    public function deleteApproval(array $data): OrderApprovalEntity;

    public function deleteDetail(array $data): OrderDetailEntity;

    public function find(int $id, array $includes = []): ?OrderEntity;

    public function findApproval(int $id, array $includes = []): ?OrderApprovalEntity;

    public function findDetail(int $id, array $includes = []): ?OrderDetailEntity;

    public function processApproval(array $ids): OrderApprovalEntitiesCollection;

    public function productionReport(OrderDetailSearchRequest $request): OrderProductionReportPDFResponse;

    public function search(OrderSearchRequest $request): OrderSearchResponse;

    public function searchApprovals(OrderApprovalSearchRequest $request): OrderApprovalSearchResponse;

    public function searchDetails(OrderDetailSearchRequest $request): OrderDetailSearchResponse;

    public function sendTicketEmail(int $id, string $email): OrderSendTicketEmailResponse;

    public function ticket(int $id): OrderTicketPDFResponse;

    public function update(array $data): OrderEntity;

    public function updateDetail(array $data): OrderDetailEntity;

    public function markUsedPurchase(array $data): OrderEntity;

    public function editNote(array $data): OrderEntity;
}
