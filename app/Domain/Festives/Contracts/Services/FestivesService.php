<?php

namespace Domain\Festives\Contracts\Services;

use Domain\Festives\DataTransferObjects\FestiveEntity;
use Domain\Festives\DataTransferObjects\FestiveSearchRequest;
use Domain\Festives\DataTransferObjects\FestiveSearchResponse;

interface FestivesService
{
    /**
     * @param array $data
     * @return FestiveEntity|null
     */
    public function create(array $data): ?FestiveEntity;

    /**
     * @param array $data
     * @return FestiveEntity|null
     */
    public function delete(array $data): ?FestiveEntity;

    /**
     * @param int $id
     * @param array $includes
     * @return FestiveEntity|null
     */
    public function find(int $id, array $includes = []): ?FestiveEntity;

    /**
     * @param FestiveSearchRequest $request
     * @return FestiveSearchResponse
     */
    public function search(FestiveSearchRequest $request): FestiveSearchResponse;

    /**
     * @param array $data
     * @return FestiveEntity|null
     */
    public function update(array $data): ?FestiveEntity;
}
