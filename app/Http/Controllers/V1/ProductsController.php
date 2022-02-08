<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'per_page' => ['nullable', 'integer']
        ]);

        return $this->successResponse(
            new ProductCollection(
                Product::paginate(Arr::get($data, 'per_page', 15))
            )
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Product $product): JsonResponse
    {
        return $this->successResponse(
            new ProductResource(
                $product
            )
        );
    }

    /**
     * @param \App\Http\Requests\StoreProductRequest $request
     * @param \App\Services\ProductService $productService
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreProductRequest $request, ProductService $productService): JsonResponse
    {
        $data = $request->validated();
        try {
            $productService->assignData([
                'name' => $data['name'],
                'description' => $data['description'],
            ]);
            return $this->successResponse('ok', ResponseAlias::HTTP_CREATED);
        } catch (\Exception $e) {
            $this->reportError($e);
            return $this->errorResponse(
                __('message.Something went wrong'),
                status: ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @param \App\Http\Requests\UpdateProductRequest $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $data = $request->validated();
        try {
            (new ProductService($product))->assignData([
                'name' => $data['name'],
                'description' => $data['description'],
            ]);
            return $this->successResponse('ok');
        } catch (\Exception $e) {
            $this->reportError($e);
            return $this->errorResponse(
                __('message.Something went wrong'),
                status: ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            $product->delete();
            return $this->successResponse('ok');
        } catch (\Exception $e) {
            $this->reportError($e);
            return $this->errorResponse(
                __('message.Something went wrong'),
                status: ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
