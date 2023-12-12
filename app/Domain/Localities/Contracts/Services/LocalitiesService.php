<?php

namespace Domain\Localities\Contracts\Services;

use Domain\Localities\DataTransferObjects\LocalityEntity;
use Domain\Localities\DataTransferObjects\LocalitySearchRequest;
use Domain\Localities\DataTransferObjects\LocalitySearchResponse;
use Domain\Localities\DataTransferObjects\ProvinceSearchRequest;
use Domain\Localities\DataTransferObjects\ProvinceSearchResponse;

interface LocalitiesService
{

    /**
     * @param array $data
     * @param array $includes
     * @return LocalityEntity|null
     */
    public function createLocality(array $data, array $includes = []): ?LocalityEntity;

    /**
     * @param int $id
     * @param array $includes
     * @return LocalityEntity|null
     */
    public function find(int $id, array $includes = []): ?LocalityEntity;

    /**
     * @param ProvinceSearchRequest $request
     * @return ProvinceSearchResponse
     */
    public function provinces(ProvinceSearchRequest $request): ProvinceSearchResponse;

    /**
     * @param LocalitySearchRequest $request
     * @return LocalitySearchResponse
     */
    public function search(LocalitySearchRequest $request): LocalitySearchResponse;
}
