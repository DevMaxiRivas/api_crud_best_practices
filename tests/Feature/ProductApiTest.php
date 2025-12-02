<?php

namespace Tests\Feature;

use App\Models\Product;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    /**
     * List products (index)
     */
    public function test_index_returns_products(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    ['id', 'name', 'details']
                ]
            ]);
    }

    /**
     * Store/create product
     */
    public function test_store_creates_product(): void
    {
        $payload = [
            'name' => 'Test product',
            'details' => 'Details for test product'
        ];

        $response = $this->postJson('/api/products', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('products', ['name' => 'Test product']);
    }

    /**
     * Store validation should return structured errors when required fields missing
     */
    public function test_store_validation_errors_when_missing_fields(): void
    {
        // empty payload
        $response = $this->postJson('/api/products', []);

        // The app's StoreProductRequest returns a JSON error payload with 'success' => false
        $response->assertStatus(200)
            ->assertJson([
                'success' => false,
                'message' => 'Validation errors'
            ])
            ->assertJsonStructure(['data' => ['name', 'details']]);
    }

    /**
     * Show product
     */
    public function test_show_returns_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'details' => $product->details,
                ]
            ]);
    }

    /**
     * Update product
     */
    public function test_update_modifies_product(): void
    {
        $product = Product::factory()->create();

        $payload = [
            'name' => 'Updated name',
            'details' => 'Updated details'
        ];

        $response = $this->putJson("/api/products/{$product->id}", $payload);

        // Controller returns a 201 with a success message
        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated name']);
    }

    /**
     * Update validation should return structured errors when required fields missing
     */
    public function test_update_validation_errors_when_missing_fields(): void
    {
        $product = Product::factory()->create();

        $response = $this->putJson("/api/products/{$product->id}", []);

        $response->assertStatus(200)
            ->assertJson([
                'success' => false,
                'message' => 'Validation errors'
            ])
            ->assertJsonStructure(['data' => ['name', 'details']]);
    }

    /**
     * Destroy product
     */
    public function test_destroy_deletes_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        // Controller returns 204 but still sends a JSON body via helper; check status and deletion
        $response->assertStatus(204);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /**
     * Show should return 404 for non-existent product
     */
    public function test_show_returns_404_for_nonexistent_product(): void
    {
        $response = $this->getJson('/api/products/999999');

        $response->assertStatus(404);
    }
}