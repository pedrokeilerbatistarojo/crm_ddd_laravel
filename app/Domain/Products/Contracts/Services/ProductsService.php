<?php

namespace Domain\Products\Contracts\Services;

use Domain\Products\DataTransferObjects\ProductDiscountEntity;
use Domain\Products\DataTransferObjects\ProductDiscountSearchRequest;
use Domain\Products\DataTransferObjects\ProductDiscountSearchResponse;
use Domain\Products\DataTransferObjects\ProductEntity;
use Domain\Products\DataTransferObjects\ProductSearchRequest;
use Domain\Products\DataTransferObjects\ProductSearchResponse;
use Domain\Products\DataTransferObjects\ProductTypeEntity;
use Domain\Products\DataTransferObjects\ProductTypeSearchRequest;
use Domain\Products\DataTransferObjects\ProductTypeSearchResponse;
use Domain\Products\DataTransferObjects\CategoryEntity;
use Domain\Products\DataTransferObjects\CategorySearchRequest;
use Domain\Products\DataTransferObjects\CategorySearchResponse;

interface ProductsService
{
    /**
     * @param array $data
     * @param array $includes
     * @return ProductEntity|null
     */
    public function create(array $data, array $includes = []): ?ProductEntity;

    /**
     * @param array $data
     * @param array $includes
     * @return ProductDiscountEntity|null
     */
    public function createProductDiscount(array $data, array $includes = []): ?ProductDiscountEntity;

    /**
     * @param array $data
     * @param array $includes
     * @return ProductTypeEntity|null
     */
    public function createProductType(array $data, array $includes = []): ?ProductTypeEntity;

    /**
     * @param array $data
     * @param array $includes
     * @return CategoryEntity|null
     */
    public function createCategory(array $data, array $includes = []): ?CategoryEntity;

    /**
     * @param array $data
     * @param array $includes
     * @return ProductEntity|null
     */
    public function delete(array $data, array $includes = []): ?ProductEntity;

    /**
     * @param array $data
     * @param array $includes
     * @return ProductDiscountEntity|null
     */
    public function deleteProductDiscount(array $data, array $includes = []): ?ProductDiscountEntity;

    /**
     * @param array $data
     * @param array $includes
     * @return ProductTypeEntity|null
     */
    public function deleteProductType(array $data, array $includes = []): ?ProductTypeEntity;

    /**
     * @param array $data
     * @param array $includes
     * @return CategoryEntity|null
     */
    public function deleteCategory(array $data, array $includes = []): ?CategoryEntity;

    /**
     * @param int $id
     * @param array $includes
     * @return ProductEntity|null
     */
    public function find(int $id, array $includes = []): ?ProductEntity;

    /**
     * @param int $id
     * @param array $includes
     * @return ProductDiscountEntity|null
     */
    public function findProductDiscount(int $id, array $includes = []): ?ProductDiscountEntity;

    /**
     * @param int $id
     * @param array $includes
     * @return ProductTypeEntity|null
     */
    public function findProductType(int $id, array $includes = []): ?ProductTypeEntity;

    /**
     * @param int $id
     * @param array $includes
     * @return CategoryEntity|null
     */
    public function findCategory(int $id, array $includes = []): ?CategoryEntity;

    /**
     * @param array $data
     * @param array $includes
     * @return ProductEntity|null
     */
    public function update(array $data, array $includes = []): ?ProductEntity;

    /**
     * @param array $data
     * @param array $includes
     * @return ProductDiscountEntity|null
     */
    public function updateProductDiscount(array $data, array $includes = []): ?ProductDiscountEntity;

    /**
     * @param array $data
     * @param array $includes
     * @return ProductTypeEntity|null
     */
    public function updateProductType(array $data, array $includes = []): ?ProductTypeEntity;

    /**
     * @param array $data
     * @param array $includes
     * @return CategoryEntity|null
     */
    public function updateCategory(array $data, array $includes = []): ?CategoryEntity;

    /**
     * @param ProductSearchRequest $request
     * @return ProductSearchResponse
     */
    public function search(ProductSearchRequest $request): ProductSearchResponse;

    /**
     * @param ProductDiscountSearchRequest $request
     * @return ProductDiscountSearchResponse
     */
    public function searchProductDiscounts(ProductDiscountSearchRequest $request): ProductDiscountSearchResponse;

    /**
     * @param ProductTypeSearchRequest $request
     * @return ProductTypeSearchResponse
     */
    public function searchProductTypes(ProductTypeSearchRequest $request): ProductTypeSearchResponse;

    /**
     * @param CategorySearchRequest $request
     * @return CategorySearchResponse
     */
    public function searchCategories(CategorySearchRequest $request): CategorySearchResponse;
}
