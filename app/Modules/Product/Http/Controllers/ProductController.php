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
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Product',
    properties: [
        new OA\Property(property: 'id', type: 'string', example: 'uuid-here'),
        new OA\Property(property: 'sku', type: 'string', example: 'SKU-001'),
        new OA\Property(property: 'name', type: 'string', example: 'Product Name'),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'price', type: 'number', example: 29.99),
        new OA\Property(property: 'stock_quantity', type: 'integer', example: 50),
        new OA\Property(property: 'low_stock_threshold', type: 'integer', example: 10),
        new OA\Property(
            property: 'status',
            type: 'string',
            enum: ['active', 'inactive', 'discontinued']
        ),
        new OA\Property(property: 'is_low_stock', type: 'boolean', example: false),
        new OA\Property(
            property: 'created_at',
            type: 'string',
            example: '2024-01-01T00:00:00.000000Z'
        ),
        new OA\Property(
            property: 'updated_at',
            type: 'string',
            example: '2024-01-01T00:00:00.000000Z'
        ),
    ]
)]
class ProductController extends Controller
{
    public function __construct(
        private readonly ProductServiceInterface $service
    ) {}

    #[OA\Get(
        path: '/api/products',
        summary: 'List all products',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(
                name: 'per_page',
                description: 'Items per page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 15)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paginated product list'
            )
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->service->list((int) $request->get('per_page', 15));

        return ApiResponse::paginated(
            ProductResource::collection($paginator->items()),
            $paginator
        );
    }

    #[OA\Post(
        path: '/api/products',
        summary: 'Create a product',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['sku', 'name', 'price'],
                properties: [
                    new OA\Property(property: 'sku', type: 'string', example: 'SKU-001'),
                    new OA\Property(property: 'name', type: 'string', example: 'Product Name'),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                    new OA\Property(property: 'price', type: 'number', example: 29.99),
                    new OA\Property(property: 'stock_quantity', type: 'integer', example: 0),
                    new OA\Property(property: 'low_stock_threshold', type: 'integer', example: 10),
                    new OA\Property(
                        property: 'status',
                        type: 'string',
                        enum: ['active', 'inactive', 'discontinued']
                    ),
                ]
            )
        ),
        tags: ['Products'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Product created'
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error'
            )
        ]
    )]
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->service->createFromDTO($request->toDTO());

        return ApiResponse::created(ProductResource::make($product));
    }

    #[OA\Get(
        path: '/api/products/{id}',
        summary: 'Get a single product',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product found'
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found'
            )
        ]
    )]
    public function show(Product $product): JsonResponse
    {
        return ApiResponse::success(ProductResource::make($product));
    }

    #[OA\Put(
        path: '/api/products/{id}',
        summary: 'Update a product',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'sku', type: 'string'),
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                    new OA\Property(property: 'price', type: 'number'),
                    new OA\Property(property: 'stock_quantity', type: 'integer'),
                    new OA\Property(property: 'low_stock_threshold', type: 'integer'),
                    new OA\Property(
                        property: 'status',
                        type: 'string',
                        enum: ['active', 'inactive', 'discontinued']
                    ),
                ]
            )
        ),
        tags: ['Products'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product updated'
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found'
            )
        ]
    )]
    public function update(
        UpdateProductRequest $request,
        Product $product
    ): JsonResponse {
        $product = $this->service->updateFromDTO($product, $request->toDTO());

        return ApiResponse::success(ProductResource::make($product));
    }

    #[OA\Delete(
        path: '/api/products/{id}',
        summary: 'Soft delete a product',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product deleted'
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found'
            )
        ]
    )]
    public function destroy(Product $product): JsonResponse
    {
        $this->service->delete($product);

        return ApiResponse::success();
    }

    #[OA\Post(
        path: '/api/products/{id}/stock',
        summary: 'Adjust product stock',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['action', 'quantity'],
                properties: [
                    new OA\Property(
                        property: 'action',
                        type: 'string',
                        enum: ['increment', 'decrement']
                    ),
                    new OA\Property(
                        property: 'quantity',
                        type: 'integer',
                        example: 5
                    ),
                ]
            )
        ),
        tags: ['Products'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Stock adjusted'
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found'
            )
        ]
    )]
    public function adjustStock(
        AdjustStockRequest $request,
        Product $product
    ): JsonResponse {
        $product = $this->service->adjustStock($product, $request->toDTO());

        return ApiResponse::success(ProductResource::make($product));
    }

    #[OA\Get(
        path: '/api/products/low-stock',
        summary: 'List products below stock threshold',
        tags: ['Products'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Low stock products'
            )
        ]
    )]
    public function lowStock(): JsonResponse
    {
        $products = $this->service->lowStock();

        return ApiResponse::success(ProductResource::collection($products));
    }
}
