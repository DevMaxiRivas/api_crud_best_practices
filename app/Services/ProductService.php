<?php

namespace App\Services;

use App\Classes\ApiResponseClass;
use App\DTOs\CreateProductDTO;
use App\DTOs\UpdateProductDTO;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Interfaces\ProductRepositoryInterface;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\DB;

class ProductService
{
    /**
     * Create a new class instance.
     */
    private ProductRepositoryInterface $repo;

    public function __construct(ProductRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return $this->repo->index();
    }

    public function createProduct(CreateProductDTO $data)
    {
        DB::beginTransaction();
        try {
            $product = $this->repo->store($data->toArray());

            DB::commit();
            return $product;
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function getById($id)
    {
        return $this->repo->getById($id);
    }


    public function updateProduct(UpdateProductDTO $data, $id)
    {
        DB::beginTransaction();
        try {
            $product = $this->repo->update($data->toArray(), $id);

            DB::commit();
            return $product;
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function deleteProduct($id)
    {
        DB::beginTransaction();
        try {
            $this->repo->delete($id);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
}
