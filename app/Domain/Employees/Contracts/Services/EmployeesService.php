<?php

namespace Domain\Employees\Contracts\Services;

use Domain\Employees\DataTransferObjects\EmployeeEntity;
use Domain\Employees\DataTransferObjects\EmployeeOrderEntity;
use Domain\Employees\DataTransferObjects\EmployeeOrderSearchRequest;
use Domain\Employees\DataTransferObjects\EmployeeOrderSearchResponse;
use Domain\Employees\DataTransferObjects\EmployeeSearchRequest;
use Domain\Employees\DataTransferObjects\EmployeeSearchResponse;
use Domain\Employees\DataTransferObjects\EmployeeTimeOffEntity;
use Domain\Employees\DataTransferObjects\EmployeeTimeOffSearchRequest;
use Domain\Employees\DataTransferObjects\EmployeeTimeOffSearchResponse;
use Domain\Employees\Models\EmployeeOrder;
use Domain\Employees\Models\EmployeeTimeOff;

interface EmployeesService
{
    /**
     * @param int $id
     * @param array $includes
     * @return EmployeeEntity|null
     */
    public function find(int $id, array $includes = []): ?EmployeeEntity;

    /**
     * @param EmployeeSearchRequest $request
     * @return EmployeeSearchResponse
     */
    public function search(EmployeeSearchRequest $request): EmployeeSearchResponse;

    /**
     * @param int $id
     * @param array $includes
     * @return EmployeeTimeOff|null
     */
    public function findEmployeeTimeOff(int $id, array $includes = []): ?EmployeeTimeOffEntity;

    /**
     * @param int $id
     * @param array $includes
     * @return EmployeeOrder|null
     */
    public function findEmployeeOrder(int $id, array $includes = []): ?EmployeeOrderEntity;

    /**
     * @param EmployeeTimeOffSearchRequest $request
     * @return EmployeeTimeOffSearchResponse
     */
    public function searchEmployeeTimeOff(EmployeeTimeOffSearchRequest $request): EmployeeTimeOffSearchResponse;

    /**
     * @param EmployeeOrderSearchRequest $request
     * @return EmployeeOrderSearchResponse
     */
    public function searchEmployeeOrder(EmployeeOrderSearchRequest $request): EmployeeOrderSearchResponse;

    /**
     * @param array $data
     * @param array $includes
     * @return EmployeeTimeOff|null
     */
    public function createEmployeeTimeOff(array $data, array $includes = []): ?EmployeeTimeOffEntity;

    /**
     * @param array $data
     * @param array $includes
     * @return EmployeeOrder|null
     */
    public function createEmployeeOrder(array $data, array $includes = []): ?EmployeeOrderEntity;

    /**
     * @param array $data
     * @param array $includes
     * @return EmployeeTimeOff|null
     */
    public function deleteEmployeeTimeOff(array $data, array $includes = []): ?EmployeeTimeOffEntity;

    /**
     * @param array $data
     * @param array $includes
     * @return EmployeeOrder|null
     */
    public function deleteEmployeeOrder(array $data, array $includes = []): ?EmployeeOrderEntity;

    /**
     * @param array $data
     * @param array $includes
     * @return EmployeeTimeOff|null
     */
    public function updateEmployeeTimeOff(array $data, array $includes = []): ?EmployeeTimeOffEntity;

    /**
     * @param array $data
     * @param array $includes
     * @return EmployeeOrder|null
     */
    public function updateEmployeeOrder(array $data, array $includes = []): ?EmployeeOrderEntity;

}
