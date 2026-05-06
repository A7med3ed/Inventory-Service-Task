<?php

namespace Tests\Feature;

use App\Modules\Product\Enums\ProductStatus;
use App\Modules\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products_with_pagination(): void
    {
        Product::factory()->count(20)->create();

        $response = $this->getJson('/api/products?per_page=10');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data',
                'meta' => ['pagination' => ['current_page', 'per_page', 'total', 'last_page']],
            ])
            ->assertJsonPath('meta.pagination.total', 20)
            ->assertJsonPath('meta.pagination.per_page', 10)
            ->assertJsonCount(10, 'data');
    }

    public function test_can_create_product(): void
    {
        $payload = [
            'sku'   => 'SKU-TEST-001',
            'name'  => 'Test Product',
            'price' => 29.99,
        ];

        $this->postJson('/api/products', $payload)
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.sku', 'SKU-TEST-001')
            ->assertJsonPath('data.status', 'active');
    }

    public function test_cannot_create_product_with_duplicate_sku(): void
    {
        Product::factory()->create(['sku' => 'DUPE-001']);

        $this->postJson('/api/products', ['sku' => 'DUPE-001', 'name' => 'x', 'price' => 1])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['sku']);
    }

    public function test_can_get_single_product(): void
    {
        $product = Product::factory()->create();

        $this->getJson("/api/products/{$product->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.sku', $product->sku);
    }

    public function test_can_update_product(): void
    {
        $product = Product::factory()->create(['name' => 'Old Name']);

        $this->putJson("/api/products/{$product->id}", ['name' => 'New Name'])
            ->assertOk()
            ->assertJsonPath('data.name', 'New Name');
    }

    public function test_can_soft_delete_product(): void
    {
        $product = Product::factory()->create();

        $this->deleteJson("/api/products/{$product->id}")->assertOk();

        $this->getJson("/api/products/{$product->id}")->assertNotFound();

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_can_increment_stock(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $this->postJson("/api/products/{$product->id}/stock", [
            'action'   => 'increment',
            'quantity' => 5,
        ])->assertOk()->assertJsonPath('data.stock_quantity', 15);
    }

    public function test_can_decrement_stock(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $this->postJson("/api/products/{$product->id}/stock", [
            'action'   => 'decrement',
            'quantity' => 3,
        ])->assertOk()->assertJsonPath('data.stock_quantity', 7);
    }

    public function test_low_stock_endpoint_returns_correct_products(): void
    {
        Product::factory()->lowStock()->count(3)->create();
        Product::factory()->create(['stock_quantity' => 50]);

        $this->getJson('/api/products/low-stock')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_validation_fails_on_missing_required_fields(): void
    {
        $this->postJson('/api/products', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['sku', 'name', 'price']);
    }
}
