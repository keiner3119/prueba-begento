<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductPrice;
use Tests\ApiTestCase;

class ProductApiTest extends ApiTestCase
{
    private function payload(array $override = []): array
    {
        return array_merge([
            'name' => 'Vitamina C',
            'description' => 'Caja x 30',
            'price' => 25.5,
            'currency_id' => Currency::factory()->create()->id,
            'tax_cost' => 4.8,
            'manufacturing_cost' => 9.99,
        ], $override);
    }

    public function test_lista_productos_paginados(): void
    {
        Product::factory()->count(3)->create();

        $this->getJson('/api/products')
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [['id', 'name', 'description', 'price', 'currency_id', 'currency', 'tax_cost', 'manufacturing_cost']],
                'links',
                'meta',
            ]);
    }

    public function test_respeta_per_page(): void
    {
        Product::factory()->count(5)->create();

        $this->getJson('/api/products?per_page=2')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.total', 5);
    }

    public function test_crea_producto(): void
    {
        $data = $this->payload();

        $this->postJson('/api/products', $data)
            ->assertCreated()
            ->assertJsonPath('data.name', 'Vitamina C')
            ->assertJsonPath('data.price', 25.5);

        $this->assertDatabaseHas('products', ['name' => 'Vitamina C']);
    }

    public function test_crear_sin_campos_obligatorios_responde_422(): void
    {
        $this->postJson('/api/products', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price', 'currency_id', 'tax_cost', 'manufacturing_cost']);
    }

    public function test_crear_con_divisa_inexistente_responde_422(): void
    {
        $this->postJson('/api/products', $this->payload(['currency_id' => 9999]))
            ->assertStatus(422)
            ->assertJsonValidationErrors('currency_id');
    }

    public function test_no_acepta_valores_negativos(): void
    {
        $this->postJson('/api/products', $this->payload(['price' => -1, 'tax_cost' => -2]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['price', 'tax_cost']);
    }

    public function test_consulta_un_producto(): void
    {
        $product = Product::factory()->create();

        $this->getJson("/api/products/{$product->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.currency.id', $product->currency_id);
    }

    public function test_producto_inexistente_responde_404_json(): void
    {
        $this->getJson('/api/products/9999')
            ->assertNotFound()
            ->assertExactJson(['message' => 'Recurso no encontrado.']);
    }

    public function test_actualiza_producto(): void
    {
        $product = Product::factory()->create();

        $this->putJson("/api/products/{$product->id}", $this->payload(['name' => 'Nuevo nombre']))
            ->assertOk()
            ->assertJsonPath('data.name', 'Nuevo nombre');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Nuevo nombre']);
    }

    public function test_actualizar_inexistente_responde_404(): void
    {
        $this->putJson('/api/products/9999', $this->payload())->assertNotFound();
    }

    public function test_elimina_producto_y_sus_precios(): void
    {
        $product = Product::factory()->create();
        ProductPrice::factory()->create(['product_id' => $product->id]);

        $this->deleteJson("/api/products/{$product->id}")->assertNoContent();

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertDatabaseCount('product_prices', 0);
    }

    public function test_eliminar_inexistente_responde_404(): void
    {
        $this->deleteJson('/api/products/9999')->assertNotFound();
    }

    public function test_lista_divisas(): void
    {
        Currency::factory()->count(2)->create();

        $this->getJson('/api/currencies')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }
}
