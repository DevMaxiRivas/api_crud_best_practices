<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Classes\ApiResponseClass;
use App\DTOs\CreateProductDTO;
use App\DTOs\UpdateProductDTO;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->productService->index();

        return ApiResponseClass::sendResponse(ProductResource::collection($data), '', 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $product = $this->productService->createProduct(
                data: CreateProductDTO::fromRequest($request)
            );
            return ApiResponseClass::sendResponse(new ProductResource($product), 'Product Create Successful', 201);
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = $this->productService->getById($id);

        return ApiResponseClass::sendResponse(new ProductResource($product), '', 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $product = $this->productService->updateProduct(
                data: UpdateProductDTO::fromRequest($request),
                id: $id
            );

            return ApiResponseClass::sendResponse('Product Update Successful', '', 201);
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->productService->deleteProduct($id);

        return ApiResponseClass::sendResponse('Product Delete Successful', '', 204);
    }
}
