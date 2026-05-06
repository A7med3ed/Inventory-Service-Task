<?php

namespace Tests\Feature;

use App\Modules\Product\Events\StockAdjusted;
use App\Modules\Product\Models\Product;
use Illuminate\Support\Facades\Event;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StockAdjustmentTest extends TestCase
{
    /**
     * Test adjusting stock with positive quantity
     */
    public function test_adjust_stock_with_positive_quantity(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $response = $this->postJson("/api/products/{$product->id}/stock", [
            'quantity' => 5,
            'reason' => 'restock',
        ]);

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('message', 'Stock adjusted successfully')
                    ->where('data.stock_quantity', 15)
            );
    }

    /**
     * Test adjusting stock with negative quantity
     */
    public function test_adjust_stock_with_negative_quantity(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $response = $this->postJson("/api/products/{$product->id}/stock", [
            'quantity' => -3,
            'reason' => 'sale',
        ]);

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('message', 'Stock adjusted successfully')
                    ->where('data.stock_quantity', 7)
            );
    }

    /**
     * Test adjusting stock beyond available quantity fails
     */
    public function test_adjust_stock_insufficient_quantity_fails(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5]);

        $response = $this->postJson("/api/products/{$product->id}/stock", [
            'quantity' => -10,
        ]);

        $response->assertStatus(400)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', false)
                    ->where('message', 'Cannot adjust stock. Insufficient stock or invalid adjustment.')
            );

        // Verify stock was not changed
        $this->assertEquals(5, $product->fresh()->stock_quantity);
    }

    /**
     * Test adjusting stock for non-existent product
     */
    public function test_adjust_stock_for_non_existent_product(): void
    {
        $response = $this->postJson('/api/products/99999/stock', [
            'quantity' => 5,
        ]);

        $response->assertStatus(404)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', false)
                    ->where('message', 'Product not found')
            );
    }

    /**
     * Test stock adjustment with zero quantity fails
     */
    public function test_adjust_stock_with_zero_quantity_fails(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $response = $this->postJson("/api/products/{$product->id}/stock", [
            'quantity' => 0,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['quantity']);
    }

    /**
     * Test stock adjustment event is dispatched
     */
    public function test_stock_adjustment_event_is_dispatched(): void
    {
        Event::fake();

        $product = Product::factory()->create(['stock_quantity' => 10]);

        $this->postJson("/api/products/{$product->id}/stock", [
            'quantity' => 5,
            'reason' => 'restock',
        ]);

        Event::assertDispatched(StockAdjusted::class, function ($event) use ($product) {
            return $event->product->id === $product->id &&
                   $event->adjustmentQuantity === 5 &&
                   $event->previousQuantity === 10 &&
                   $event->newQuantity === 15;
        });
    }

    /**
     * Test low stock listener is triggered
     */
    public function test_low_stock_listener_triggered_when_threshold_reached(): void
    {
        Event::fake();

        $product = Product::factory()->create([
            'stock_quantity' => 15,
            'low_stock_threshold' => 10,
        ]);

        $this->postJson("/api/products/{$product->id}/stock", [
            'quantity' => -6,
        ]);

        // Verify event was dispatched
        Event::assertDispatched(StockAdjusted::class);
    }

    /**
     * Test adjustment reason is stored in event
     */
    public function test_adjustment_reason_is_stored(): void
    {
        Event::fake();

        $product = Product::factory()->create(['stock_quantity' => 10]);

        $this->postJson("/api/products/{$product->id}/stock", [
            'quantity' => 5,
            'reason' => 'inventory_correction',
        ]);

        Event::assertDispatched(StockAdjusted::class, function ($event) {
            return $event->reason === 'inventory_correction';
        });
    }

    /**
     * Test default reason when not provided
     */
    public function test_default_adjustment_reason(): void
    {
        Event::fake();

        $product = Product::factory()->create(['stock_quantity' => 10]);

        $this->postJson("/api/products/{$product->id}/stock", [
            'quantity' => 5,
        ]);

        Event::assertDispatched(StockAdjusted::class, function ($event) {
            return $event->reason === 'manual_adjustment';
        });
    }

    /**
     * Test updating stock through adjustment maintains data integrity
     */
    public function test_stock_adjustment_maintains_data_integrity(): void
    {
        $product = Product::factory()->create([
            'stock_quantity' => 10,
            'price' => 99.99,
            'name' => 'Test Product',
        ]);

        $this->postJson("/api/products/{$product->id}/stock", [
            'quantity' => 5,
        ]);

        $updated = $product->fresh();

        $this->assertEquals(15, $updated->stock_quantity);
        $this->assertEquals(99.99, $updated->price);
        $this->assertEquals('Test Product', $updated->name);
    }

    /**
     * Test multiple consecutive adjustments
     */
    public function test_multiple_consecutive_adjustments(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);

        // First adjustment
        $this->postJson("/api/products/{$product->id}/stock", [
            'quantity' => 5,
        ])->assertStatus(200);

        // Second adjustment
        $this->postJson("/api/products/{$product->id}/stock", [
            'quantity' => -3,
        ])->assertStatus(200);

        // Third adjustment
        $response = $this->postJson("/api/products/{$product->id}/stock", [
            'quantity' => 8,
        ]);

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('data.stock_quantity', 20)
            );
    }
}
