<?php

namespace Domain\Products\Services;

use DateTime;
use Domain\Products\Actions\DeleteProduct;
use Domain\Products\Actions\DeleteProductDiscount;
use Domain\Products\Actions\DeleteProductType;
use Domain\Products\Actions\DeleteCategory;
use Domain\Products\Actions\UpsertProduct;
use Domain\Products\Actions\UpsertProductDiscount;
use Domain\Products\Actions\UpsertProductType;
use Domain\Products\Actions\UpsertCategory;
use Domain\Products\Contracts\Repositories\ProductDiscountsRepository;
use Domain\Products\Contracts\Repositories\ProductsRepository;
use Domain\Products\Contracts\Repositories\ProductTypesRepository;
use Domain\Products\Contracts\Repositories\CategoriesRepository;
use Domain\Products\DataTransferObjects\ProductDiscountEntitiesCollection;
use Domain\Products\DataTransferObjects\ProductDiscountEntity;
use Domain\Products\DataTransferObjects\ProductDiscountSearchRequest;
use Domain\Products\DataTransferObjects\ProductDiscountSearchResponse;
use Domain\Products\DataTransferObjects\ProductEntitiesCollection;
use Domain\Products\DataTransferObjects\ProductEntity;
use Domain\Products\DataTransferObjects\ProductSearchRequest;
use Domain\Products\DataTransferObjects\ProductSearchResponse;
use Domain\Products\DataTransferObjects\ProductTypeEntitiesCollection;
use Domain\Products\DataTransferObjects\ProductTypeEntity;
use Domain\Products\DataTransferObjects\ProductTypeSearchRequest;
use Domain\Products\DataTransferObjects\ProductTypeSearchResponse;
use Domain\Products\DataTransferObjects\CategoryEntitiesCollection;
use Domain\Products\DataTransferObjects\CategoryEntity;
use Domain\Products\DataTransferObjects\CategorySearchRequest;
use Domain\Products\DataTransferObjects\CategorySearchResponse;
use Domain\Products\Models\Product;
use Domain\Products\Models\ProductDiscount;
use Domain\Products\Models\ProductType;
use Domain\Products\Models\Category;
use Domain\Products\Transformers\ProductDiscountTransformer;
use Domain\Products\Transformers\ProductTransformer;
use Domain\Products\Transformers\ProductTypeTransformer;
use Domain\Products\Transformers\CategoryTransformer;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Core\Enums\SQLSort;
use Support\Core\Serializers\FractalDataArraySerializer;
use Support\Exceptions\InvalidDataTypeException;

class ProductsService implements \Domain\Products\Contracts\Services\ProductsService
{
    /**
     * @var ProductsRepository
     */
    protected ProductsRepository $repository;

    /**
     * @var ProductDiscountsRepository
     */
    protected ProductDiscountsRepository $productDiscountsRepository;

    /**
     * @var ProductTypesRepository
     */
    protected ProductTypesRepository $productTypesRepository;

    /**
     * @var CategoriesRepository
     */
    protected CategoriesRepository $categoriesRepository;

    /**
     * @param ProductsRepository $repository
     * @param ProductDiscountsRepository $productDiscountsRepository
     * @param ProductTypesRepository $productTypesRepository
     * @param CategoriesRepository $categoriesRepository
     */
    public function __construct(
        ProductsRepository $repository,
        ProductDiscountsRepository $productDiscountsRepository,
        ProductTypesRepository $productTypesRepository,
        CategoriesRepository $categoriesRepository,
    ) {
        $this->repository = $repository;
        $this->productDiscountsRepository = $productDiscountsRepository;
        $this->productTypesRepository = $productTypesRepository;
        $this->categoriesRepository = $categoriesRepository;
    }

    /**
     * @param array $data
     * @param array $includes
     * @return ProductEntity
     * @throws UnknownProperties
     */
    public function create(array $data, array $includes = []): ProductEntity
    {
        $this->orderByProductPriority($data);

        $record = app(UpsertProduct::class)($data);

        $this->priorityProductsNumerator();

        return $this->productDTOFromModel($record, $includes);
    }

    /**
     * @param array $data
     * @param array $includes
     * @return ProductDiscountEntity
     * @throws UnknownProperties
     */
    public function createProductDiscount(array $data, array $includes = []): ProductDiscountEntity
    {
        $record = app(UpsertProductDiscount::class)($data);

        return $this->productDiscountDTOFromModel($record, $includes);
    }

    /**
     * @param array $data
     * @param array $includes
     * @return ProductTypeEntity
     * @throws UnknownProperties
     */
    public function createProductType(array $data, array $includes = []): ProductTypeEntity
    {
        $this->orderByProductTypePriority($data);

        $record = app(UpsertProductType::class)($data);

        $this->priorityProductTypeNumerator();

        return $this->productTypeDTOFromModel($record, $includes);
    }

    /**
     * @param array $data
     * @param array $includes
     * @return ProductEntity
     * @throws UnknownProperties
     */
    public function delete(array $data, array $includes = []): ProductEntity
    {
        $record = app(DeleteProduct::class)($data);

        return $this->productDTOFromModel($record, $includes);
    }

    /**
     * @param array $data
     * @param array $includes
     * @return ProductDiscountEntity
     * @throws UnknownProperties
     */
    public function deleteProductDiscount(array $data, array $includes = []): ProductDiscountEntity
    {
        $record = app(DeleteProductDiscount::class)($data);

        return $this->productDiscountDTOFromModel($record, $includes);
    }

    /**
     * @param array $data
     * @param array $includes
     * @return ProductTypeEntity
     * @throws UnknownProperties
     */
    public function deleteProductType(array $data, array $includes = []): ProductTypeEntity
    {
        $record = app(DeleteProductType::class)($data);

        return $this->productTypeDTOFromModel($record, $includes);
    }

    /**
     * @param array $data
     * @param array $includes
     * @return ProductEntity
     * @throws UnknownProperties
     */
    public function update(array $data, array $includes = []): ProductEntity
    {
        $this->orderByProductPriority($data);

        $record = app(UpsertProduct::class)($data);

        $this->priorityProductsNumerator();

        return $this->productDTOFromModel($record, $includes);
    }

    /**
     * @param array $data
     * @param array $includes
     * @return ProductDiscountEntity
     * @throws UnknownProperties
     */
    public function updateProductDiscount(array $data, array $includes = []): ProductDiscountEntity
    {
        $record = app(UpsertProductDiscount::class)($data);

        return $this->productDiscountDTOFromModel($record, $includes);
    }

    /**
     * @param array $data
     * @param array $includes
     * @return ProductTypeEntity
     * @throws UnknownProperties
     */
    public function updateProductType(array $data, array $includes = []): ProductTypeEntity
    {
        $this->orderByProductTypePriority($data);

        $record = app(UpsertProductType::class)($data);

        $this->priorityProductTypeNumerator();

        return $this->productTypeDTOFromModel($record);
    }

    /**
     * @param int $id
     * @param array $includes
     * @return ProductDiscountEntity|null
     * @throws UnknownProperties
     */
    public function findProductDiscount(int $id, array $includes = []): ?ProductDiscountEntity
    {
        if (!$record = $this->productDiscountsRepository->find($id)) {
            return null;
        }

        return $this->productDiscountDTOFromModel($record, $includes);
    }

    /**
     * @param int $id
     * @param array $includes
     * @return ProductTypeEntity|null
     * @throws UnknownProperties
     */
    public function findProductType(int $id, array $includes = []): ?ProductTypeEntity
    {
        if (!$record = $this->productTypesRepository->find($id)) {
            return null;
        }

        return $this->productTypeDTOFromModel($record, $includes);
    }

    /**
     * @param int $id
     * @param array $includes
     * @return ProductEntity|null
     * @throws UnknownProperties
     */
    public function find(int $id, array $includes = []): ?ProductEntity
    {
        if (!$record = $this->repository->find($id)) {
            return null;
        }

        return $this->productDTOFromModel($record, $includes);
    }

    /**
     * @param string $name
     * @param array $includes
     * @return ProductEntity|null
     * @throws UnknownProperties
     */
    public function findByName(string $name, array $includes = []): ?ProductEntity
    {
        if (!$record = $this->repository->getFirstByFields(['name' => $name])) {
            return null;
        }

        return $this->productDTOFromModel($record, $includes);
    }

    /**
     * @param ProductDiscountSearchRequest $request
     * @return ProductDiscountSearchResponse
     * @throws InvalidDataTypeException
     */
    public function searchProductDiscounts(ProductDiscountSearchRequest $request): ProductDiscountSearchResponse
    {
        $query = $this->productDiscountsRepository->searchQueryBuilder(
            $request->filters,
            $request->sortField,
            $request->sortType
        );
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(ProductDiscountTransformer::class), 'data');
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new ProductDiscountSearchResponse('Ok'))->setData(
            ProductDiscountEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param ProductTypeSearchRequest $request
     * @return ProductTypeSearchResponse
     * @throws InvalidDataTypeException
     */
    public function searchProductTypes(ProductTypeSearchRequest $request): ProductTypeSearchResponse
    {
        $query = $this->productTypesRepository->searchQueryBuilder(
            $request->filters,
            $request->sortField,
            $request->sortType
        );
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(ProductTypeTransformer::class), 'data');
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new ProductTypeSearchResponse('Ok'))->setData(
            ProductTypeEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param ProductSearchRequest $request
     * @return ProductSearchResponse
     * @throws InvalidDataTypeException
     * @throws UnknownProperties
     */
    public function search(ProductSearchRequest $request): ProductSearchResponse
    {
        $query = $this->repository->searchQueryBuilder(
            [...$request->filters],
            $request->sortField,
            $request->sortType
        );
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(ProductTransformer::class), 'data');
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new ProductSearchResponse('Ok'))->setData(
            ProductEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param Product $entity
     * @param array $includes
     * @return ProductEntity
     * @throws UnknownProperties
     */
    private function productDTOFromModel(Product $entity, array $includes = []): ProductEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(ProductTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new ProductEntity($data);
    }

    /**
     * @param ProductDiscount $entity
     * @param array $includes
     * @return ProductDiscountEntity
     * @throws UnknownProperties
     */
    private function productDiscountDTOFromModel(ProductDiscount $entity, array $includes = []): ProductDiscountEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(ProductDiscountTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new ProductDiscountEntity($data);
    }

    /**
     * @param ProductType $entity
     * @param array $includes
     * @return ProductTypeEntity
     * @throws UnknownProperties
     */
    private function productTypeDTOFromModel(ProductType $entity, array $includes = []): ProductTypeEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(ProductTypeTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new ProductTypeEntity($data);
    }

    /**
     * @param Category $entity
     * @param array $includes
     * @return CategoryEntity
     * @throws UnknownProperties
     */
    private function categoryDTOFromModel(Category $entity, array $includes = []): CategoryEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(CategoryTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new CategoryEntity($data);
    }

    /**
     * @param array $data
     * @param array $includes
     * @return CategoryEntity
     * @throws UnknownProperties
     */
    public function createCategory(array $data, array $includes = []): CategoryEntity
    {
        $record = app(UpsertCategory::class)($data);

        return $this->categoryDTOFromModel($record, $includes);
    }

    /**
     * @param array $data
     * @param array $includes
     * @return CategoryEntity
     * @throws UnknownProperties
     */
    public function deleteCategory(array $data, array $includes = []): CategoryEntity
    {
        $record = app(DeleteCategory::class)($data);

        return $this->categoryDTOFromModel($record, $includes);
    }

    /**
     * @param int $id
     * @param array $includes
     * @return CategoryEntity|null
     * @throws UnknownProperties
     */
    public function findCategory(int $id, array $includes = []): ?CategoryEntity
    {
        if (!$record = $this->categoriesRepository->find($id)) {
            return null;
        }

        return $this->categoryDTOFromModel($record, $includes);
    }

    /**
     * @param array $data
     * @param array $includes
     * @return CategoryEntity
     * @throws UnknownProperties
     */
    public function updateCategory(array $data, array $includes = []): CategoryEntity
    {
        $record = app(UpsertCategory::class)($data);

        return $this->categoryDTOFromModel($record);
    }

    /**
     * @param CategorySearchRequest $request
     * @return CategorySearchResponse
     * @throws InvalidDataTypeException
     */
    public function searchCategories(CategorySearchRequest $request): CategorySearchResponse
    {
        $query = $this->categoriesRepository->searchQueryBuilder(
            $request->filters,
            $request->sortField,
            $request->sortType
        );

        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(CategoryTransformer::class), 'data');
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new CategorySearchResponse('Ok'))->setData(
            CategoryEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    private function orderByProductTypePriority($data)
    {
        $priority = $data['priority'];

        if(isset($data['id'])){
            $productType = ProductType::find($data['id']);
            if($priority < $productType->priority){
                $currentPriorities = ProductType::where('priority', '>=', $priority)->get();
                foreach ($currentPriorities as $currentPriority) {
                    $currentPriority->priority += 1;
                    $currentPriority->save();
                }
            }else{
                $currentPriorities = ProductType::where('priority', '>', $priority)->get();
                foreach ($currentPriorities as $currentPriority) {
                    $currentPriority->priority -= 1;
                    $currentPriority->save();
                }
            }
        }else{
            $currentPriorities = ProductType::where('priority', '>', $priority)->get();
            foreach ($currentPriorities as $currentPriority) {
                $currentPriority->priority -= 1;
                $currentPriority->save();
            }
            $currentPriorities = ProductType::where('priority', '>=', $priority)->get();
            foreach ($currentPriorities as $currentPriority) {
                $currentPriority->priority += 1;
                $currentPriority->save();
            }
        }
    }

    private function orderByProductPriority($data): void
    {
        $priority = $data['priority'];
        $product_type_id = $data['product_type_id'];
        
        if(isset($data['id'])){
            $product = Product::find($data['id']);
            if($priority < $product->priority){
                $currentPriorities = Product::where('priority', '>=', $priority)
                ->where('product_type_id', $product_type_id)->get();

                foreach ($currentPriorities as $currentPriority) {
                    $currentPriority->priority += 1;
                    $currentPriority->save();
                }
            }else{
                $currentPriorities = Product::where('priority', '>', $priority)
                ->where('product_type_id', $product_type_id)->get();
                foreach ($currentPriorities as $currentPriority) {
                    $currentPriority->priority -= 1;
                    $currentPriority->save();
                }
            }
        }else{
            $currentPriorities = Product::where('priority', '>', $priority)
                ->where('product_type_id', $product_type_id)->get();
            foreach ($currentPriorities as $currentPriority) {
                $currentPriority->priority -= 1;
                $currentPriority->save();
            }
            $currentPriorities = Product::where('priority', '>=', $priority)
                ->where('product_type_id', $product_type_id)->get();
            foreach ($currentPriorities as $currentPriority) {
                $currentPriority->priority += 1;
                $currentPriority->save();
            }
        }
    }

    private function priorityProductTypeNumerator()
    {
        $collection = ProductType::orderBy('priority')->get();
        foreach ($collection as $key => $object) {
            $object->update(['priority' => $key + 1]);
        }
    }

    private function priorityProductsNumerator()
    {
        $productTypes = ProductType::orderBy('priority')->get();
        foreach ($productTypes as $productType) {
            $products = Product::where('product_type_id', $productType->id)
                ->orderBy('priority')->get();
            foreach ($products as $key => $product) {
                $product->update(['priority' => $key + 1]);
            }
        }
    }
}
