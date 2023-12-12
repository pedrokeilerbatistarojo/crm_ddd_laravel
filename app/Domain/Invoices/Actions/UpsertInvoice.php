<?php

namespace Domain\Invoices\Actions;

use Domain\Invoices\Contracts\Repositories\InvoicesRepository;
use Domain\Invoices\Enums\InvoiceType;
use Domain\Invoices\Models\Invoice;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpsertInvoice
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
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function __invoke(array $data): Invoice
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
            'client_id' => 'required|numeric',
            'number' => 'required',
            'description' => 'required',
            'invoice_type' => 'required|in:' . implode(',', collect(InvoiceType::cases())->pluck('value')->toArray()),
            'invoice_date' => 'required|date|date_format:Y-m-d H:i:s',
            'address' => 'required',
            'zip_code' => 'required',
            'locality' => 'required',
            'province' => 'required',
            'observations' => 'required',
        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:invoices';
        }

        return $rules;
    }
}
