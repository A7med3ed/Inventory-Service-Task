<?php

namespace App\Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\Responses\ApiResponse;
use App\Modules\Product\Contracts\ProductServiceInterface;
use App\Modules\Product\DTOs\AdjustStockDTO;
use App\Modules\Product\DTOs\CreateProductDTO;
use App\Modules\Product\DTOs\UpdateProductDTO;
use App\Modules\Product\Http\Requests\AdjustStockRequest;
use App\Modules\Product\Http\Requests\CreateProductRequest;
use App\Modules\Product\Http\Requests\UpdateProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ProductServiceInterface $productService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $products = $this->productService->getProductsPaginated($perPage);

        return ApiResponse::paginated($products, 'Products retrieved successfully');
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return ApiResponse::notFound('Product not found');
        }

        return ApiResponse::success($product, 'Product retrieved successfully');
    }

    public function store(CreateProductRequest $request): JsonResponse
    {
        $dto = CreateProductDTO::fromArray($request->validated());
        $product = $this->productService->createProduct($dto);

        return ApiResponse::success($product, 'Product created successfully', 201);
    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $dto = UpdateProductDTO::fromArray($request->validated());
        $updated = $this->productService->updateProduct($id, $dto);

        if (!$updated) {
            return ApiResponse::notFound('Product not found');
        }

        $product = $this->productService->getProductById($id);
        return ApiResponse::success($product, 'Product updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->productService->deleteProduct($id);

        if (!$deleted) {
            return ApiResponse::notFound('Product not found');
        }

        return ApiResponse::success(null, 'Product deleted successfully');
    }

    public function active(): JsonResponse
    {
        $products = $this->productService->getActiveProducts();
        return ApiResponse::success($products, 'Active products retrieved successfully');
    }

    public function lowStock(Request $request): JsonResponse
    {
        $threshold = $request->get('threshold', 10);
        $products = $this->productService->checkLowStock($threshold);
        return ApiResponse::success($products, 'Low stock products retrieved successfully');
    }

    public function byCategory(int $categoryId): JsonResponse
    {
        $products = $this->productService->getProductsByCategory($categoryId);
        return ApiResponse::success($products, 'Products by category retrieved successfully');
    }

    public function adjustStock(AdjustStockRequest $request, int $id): JsonResponse
    {
        $dto = AdjustStockDTO::fromArray([
            'product_id' => $id,
            'quantity' => $request->validated()['quantity'],
            'reason' => $request->validated()['reason'] ?? 'manual_adjustment',
        ]);

        $success = $this->productService->adjustStock($id, $dto);

        if (!$success) {
            $product = $this->productService->getProductById($id);
            if (!$product) {
                return ApiResponse::notFound('Product not found');
            }
            return ApiResponse::error('Cannot adjust stock. Insufficient stock or invalid adjustment.', 400);
        }

        $product = $this->productService->getProductById($id);
        return ApiResponse::success($product, 'Stock adjusted successfully');
    }
}
