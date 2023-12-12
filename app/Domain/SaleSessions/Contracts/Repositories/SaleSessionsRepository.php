<?php

namespace Domain\SaleSessions\Contracts\Repositories;

use Domain\SaleSessions\Models\SaleSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Support\Core\Enums\SQLSort;
use Support\Repositories\Contracts\Repository;

interface SaleSessionsRepository extends Repository
{
    public function activeSession(): ?SaleSession;

    public function lastSession(): ?SaleSession;

    public function search(array $filters, string $sortField, SQLSort $sortType): Collection;

    public function searchQueryBuilder(array $filters, string $sortField, SQLSort $sortType): Builder;
}
