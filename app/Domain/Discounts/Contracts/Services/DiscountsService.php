<?php

namespace Domain\Discounts\Contracts\Services;

use Domain\Discounts\DataTransferObjects\DiscountEntity;
use Domain\Discounts\DataTransferObjects\DiscountSearchRequest;
use Domain\Discounts\DataTransferObjects\DiscountSearchResponse;

interface DiscountsService
{
    /**
     * @param array $data
     * @return DiscountEntity|null
     */
    public function create(array $data): ?DiscountEntity;

    /**
     * @param array $data
     * @return DiscountEntity|null
     */
    public function delete(array $data): ?DiscountEntity;

    /**
     * @param int $id
     * @param array $includes
     * @return DiscountEntity|null
     */
    public function find(int $id, array $includes = []): ?DiscountEntity;

    /**
     * @param DiscountSearchRequest $request
     * @return DiscountSearchResponse
     */
    public function search(DiscountSearchRequest $request): DiscountSearchResponse;

    /**
     * @param array $data
     * @return DiscountEntity|null
     */
    public function update(array $data): ?DiscountEntity;
}
