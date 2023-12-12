<?php

namespace Domain\Employees\Actions;

use Domain\Employees\Contracts\Repositories\EmployeesRepository;
use Domain\Employees\Models\Employee;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteEmployee
{
    private EmployeesRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param EmployeesRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        EmployeesRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return Employee
     * @throws ValidationException
     */
    public function __invoke(array $data): Employee
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
        return ['id' => 'required|exists:employees'];
    }
}
