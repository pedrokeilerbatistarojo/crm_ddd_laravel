<?php

namespace Domain\Employees\Actions;


use Domain\Employees\Contracts\Repositories\EmployeeOrderRepository;
use Domain\Employees\Models\EmployeeOrder;
use Domain\Employees\Repositories\EmployeeTimeOffRepository;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Support\Exceptions\DatabaseException;

class DeleteEmployeeOrder
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
     * @throws ValidationException
     */
    public function __invoke(array $data): EmployeeOrder
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
        return ['id' => 'required|exists:employees_order'];
    }
}
