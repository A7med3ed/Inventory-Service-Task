<?php

namespace App\Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\Responses\ApiResponse;
use App\Modules\Product\Contracts\ProductServiceInterface;
use App\Modules\Product\Http\Requests\AdjustStockRequest;
use App\Modules\Product\Http\Requests\StoreProductRequest;
use App\Modules\Product\Http\Requests\UpdateProductRequest;
use App\Modules\Product\Http\Resources\ProductResource;
use App\Modules\Product\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductServiceInterface $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->service->list((int) $request->get('per_page', 15));

        return ApiResponse::paginated(
            ProductResource::collection($paginator->items()),
            $paginator
        );
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->service->createFromDTO($request->toDTO());

        return ApiResponse::created(ProductResource::make($product));
    }

    public function show(Product $product): JsonResponse
    {
        return ApiResponse::success(ProductResource::make($product));
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product = $this->service->updateFromDTO($product, $request->toDTO());

        return ApiResponse::success(ProductResource::make($product));
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->service->delete($product);

        return ApiResponse::success();
    }

    public function adjustStock(AdjustStockRequest $request, Product $product): JsonResponse
    {
        $product = $this->service->adjustStock($product, $request->toDTO());

        return ApiResponse::success(ProductResource::make($product));
    }

    public function lowStock(): JsonResponse
    {
        $products = $this->service->lowStock();

        return ApiResponse::success(ProductResource::collection($products));
    }
}
