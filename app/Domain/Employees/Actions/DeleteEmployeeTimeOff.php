<?php

namespace Domain\Employees\Actions;


use Domain\Employees\Models\EmployeeTimeOff;
use Domain\Employees\Repositories\EmployeeTimeOffRepository;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Support\Exceptions\DatabaseException;

class DeleteEmployeeTimeOff
{
    private EmployeeTimeOffRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param EmployeeTimeOffRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        EmployeeTimeOffRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return EmployeeTimeOff
     * @throws ValidationException|DatabaseException
     */
    public function __invoke(array $data): EmployeeTimeOff
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
        return ['id' => 'required|exists:employee_time_off'];
    }
}
