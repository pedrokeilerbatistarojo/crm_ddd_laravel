<?php

namespace Domain\Invoices\Actions;

use Domain\Invoices\Contracts\Repositories\InvoicesRepository;
use Domain\Invoices\Models\Invoice;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteInvoice
{
    private InvoicesRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param InvoicesRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        InvoicesRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return Invoice
     * @throws ValidationException
     */
    public function __invoke(array $data): Invoice
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
            'id' => 'required|exists:invoices'
        ];
    }
}
