<?php

namespace Domain\Employees\Actions;

use Domain\Employees\Contracts\Repositories\EmployeeTimeOffRepository;
use Domain\Employees\Models\EmployeeTimeOff;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpsertEmployeeTimeOff
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
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function __invoke(array $data): EmployeeTimeOff
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
            'employee_id' => 'required',
            'type' => 'required|in:Día Completo,Horas',
            'from_date' => 'required',
            'to_date' => 'required',
        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:employee_time_off';
        }

        return $rules;
    }
}
