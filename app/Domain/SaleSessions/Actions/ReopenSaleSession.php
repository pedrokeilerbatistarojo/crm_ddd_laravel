<?php

namespace Domain\SaleSessions\Actions;

use Domain\SaleSessions\Contracts\Repositories\SaleSessionsRepository;
use Domain\SaleSessions\Enums\SessionStatus;
use Domain\SaleSessions\Models\SaleSession;
use Illuminate\Contracts\Validation\Factory;

class ReopenSaleSession
{
    private SaleSessionsRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param SaleSessionsRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        SaleSessionsRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @return SaleSession
     * @throws \Throwable
     */
    public function __invoke(): SaleSession
    {
        throw_if($this->repository->activeSession(), 'Session already opened');

        $lastSession = $this->repository->lastSession();

        return $this->repository->edit([
            'id' => $lastSession->id,
            'session_status' => SessionStatus::REOPENED,
            'end_date' => null,
            'end_amount' => null,
            'closed_by' => null
        ]);
    }
}
