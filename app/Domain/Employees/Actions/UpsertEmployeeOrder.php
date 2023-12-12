<?php

namespace Domain\Employees\Actions;

use Domain\Employees\Contracts\Repositories\EmployeeOrderRepository;
use Domain\Employees\Models\EmployeeOrder;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpsertEmployeeOrder
{
    private EmployeeOrderRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param EmployeeOrderRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        EmployeeOrderRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return EmployeeOrder
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function __invoke(array $data): EmployeeOrder
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
            'date' => 'required|unique:employees_order,date',
            'order' => 'required',
        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:employees_order';
            $rules['date'] .= ',' . $data['id'] . ',id';
        }

        return $rules;
    }
}
