<?php

namespace Domain\CircuitReservations\Contracts\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Support\Core\Enums\SQLSort;
use Support\Repositories\Contracts\Repository;

interface CircuitReservationsRepository extends Repository
{
    public function findByOrderDetail(int $id): Collection;

    public function relatedOrderDetails(int $id, array $columns = ['*']): Collection;

    public function search(array $filters, string $sortField, SQLSort $sortType): Collection;

    public function searchQueryBuilder(array $filters, string $sortField, SQLSort $sortType): Builder;
}
