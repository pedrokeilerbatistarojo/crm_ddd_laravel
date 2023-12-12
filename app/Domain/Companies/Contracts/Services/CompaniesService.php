<?php

namespace Domain\Companies\Contracts\Services;

use Domain\Companies\DataTransferObjects\CompanyEntity;

interface CompaniesService
{
    /**
     * @param int $id
     * @param array $includes
     * @return CompanyEntity|null
     */
    public function find(int $id, array $includes = []): ?CompanyEntity;
}
