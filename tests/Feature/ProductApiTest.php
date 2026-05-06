<?php

namespace Tests\Feature;

use App\Modules\Product\Models\Product;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    /**
     * Test listing all products with pagination
     */
    public function test_list_products_with_pagination(): void
    {
        Product::factory()->count(20)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [],
                'meta' => [
                    'pagination' => [
                        'total',
                        'per_page',
                        'current_page',
                        'last_page',
                        'from',
                        'to',
                    ]
                ]
            ])
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('message', 'Products retrieved successfully')
                    ->has('data', 15)
            );
    }

    /**
     * Test getting a single product
     */
    public function test_get_single_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('data.id', $product->id)
                    ->where('data.name', $product->name)
                    ->where('data.sku', $product->sku)
                    ->hasAll(['data.price', 'data.stock_quantity', 'data.status'])
            );
    }

    /**
     * Test getting non-existent product
     */
    public function test_get_non_existent_product(): void
    {
        $response = $this->getJson('/api/products/99999');

        $response->assertStatus(404)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', false)
                    ->where('message', 'Product not found')
            );
    }

    /**
     * Test creating a product with valid data
     */
    public function test_create_product_with_valid_data(): void
    {
        $data = [
            'name' => 'Test Product',
            'slug' => 'test-product',
            'sku' => 'SKU-001',
            'description' => 'Test description',
            'price' => 99.99,
            'stock_quantity' => 50,
            'low_stock_threshold' => 10,
            'status' => 'active',
            'is_active' => true,
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('message', 'Product created successfully')
                    ->where('data.name', 'Test Product')
                    ->where('data.sku', 'SKU-001')
                    ->where('data.status', 'active')
            );
    }

    /**
     * Test creating product with duplicate SKU fails
     */
    public function test_create_product_with_duplicate_sku_fails(): void
    {
        $product = Product::factory()->create();

        $data = [
            'name' => 'Another Product',
            'slug' => 'another-product',
            'sku' => $product->sku,
            'description' => 'Test description',
            'price' => 99.99,
            'stock_quantity' => 50,
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', false)
            );
    }

    /**
     * Test creating product with missing required fields
     */
    public function test_create_product_with_missing_fields(): void
    {
        $data = [
            'name' => 'Test Product',
            'slug' => 'test-product',
            // sku is required but missing
            'description' => 'Test description',
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['sku', 'price', 'stock_quantity']);
    }

    /**
     * Test updating a product
     */
    public function test_update_product(): void
    {
        $product = Product::factory()->create();

        $data = [
            'name' => 'Updated Product',
            'price' => 149.99,
            'stock_quantity' => 100,
        ];

        $response = $this->putJson("/api/products/{$product->id}", $data);

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('message', 'Product updated successfully')
                    ->where('data.name', 'Updated Product')
                    ->where('data.price', '149.99')
            );
    }

    /**
     * Test updating non-existent product
     */
    public function test_update_non_existent_product(): void
    {
        $data = [
            'name' => 'Updated Product',
        ];

        $response = $this->putJson('/api/products/99999', $data);

        $response->assertStatus(404)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', false)
                    ->where('message', 'Product not found')
            );
    }

    /**
     * Test soft deleting a product
     */
    public function test_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('message', 'Product deleted successfully')
            );

        // Verify product is soft deleted
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    /**
     * Test getting active products
     */
    public function test_get_active_products(): void
    {
        Product::factory()->count(5)->create(['is_active' => true, 'stock_quantity' => 10]);
        Product::factory()->count(3)->create(['is_active' => false]);

        $response = $this->getJson('/api/products/active');

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('message', 'Active products retrieved successfully')
                    ->has('data', 5)
            );
    }

    /**
     * Test getting low stock products
     */
    public function test_get_low_stock_products(): void
    {
        Product::factory()->create(['stock_quantity' => 5, 'low_stock_threshold' => 10]);
        Product::factory()->create(['stock_quantity' => 50, 'low_stock_threshold' => 10]);

        $response = $this->getJson('/api/products/low-stock?threshold=10');

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('message', 'Low stock products retrieved successfully')
            );
    }

    /**
     * Test response format consistency
     */
    public function test_response_format_consistency(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertJsonStructure([
            'success',
            'message',
            'data',
            'meta',
        ]);
    }
}
