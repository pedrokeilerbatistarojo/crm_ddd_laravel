<?php

namespace Domain\Employees\Actions;

use Domain\Employees\Contracts\Repositories\EmployeesRepository;
use Domain\Employees\Models\Employee;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpsertEmployee
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
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function __invoke(array $data): Employee
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
            'email' => 'required|email|unique:employees,email',
            'first_name' => 'required',
            'last_name' => 'required',
            'second_last_name' => 'nullable',
            'phone' => 'nullable',
            'active' => 'required|boolean',
            'is_specialist' => 'required|boolean'
        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:employees';
            $rules['email'] .= ',' . $data['id'] . ',id';
        }

        return $rules;
    }
}
