<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductPrice;
use Tests\ApiTestCase;

class ProductPriceApiTest extends ApiTestCase
{
    public function test_lista_precios_del_producto(): void
    {
        $product = Product::factory()->create();
        ProductPrice::factory()->count(2)->create(['product_id' => $product->id]);
        ProductPrice::factory()->create(); // de otro producto

        $this->getJson("/api/products/{$product->id}/prices")
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure(['data' => [['id', 'product_id', 'currency_id', 'currency', 'price']]]);
    }

    public function test_listar_precios_de_producto_inexistente_responde_404(): void
    {
        $this->getJson('/api/products/9999/prices')->assertNotFound();
    }

    public function test_crea_precio_en_otra_divisa(): void
    {
        $product = Product::factory()->create();
        $cop = Currency::factory()->create();

        $this->postJson("/api/products/{$product->id}/prices", [
            'currency_id' => $cop->id,
            'price' => 100000,
        ])
            ->assertCreated()
            ->assertJsonPath('data.price', 100000)
            ->assertJsonPath('data.currency.id', $cop->id);

        $this->assertDatabaseHas('product_prices', [
            'product_id' => $product->id,
            'currency_id' => $cop->id,
        ]);
    }

    public function test_crear_precio_en_producto_inexistente_responde_404(): void
    {
        $this->postJson('/api/products/9999/prices', ['currency_id' => 1, 'price' => 10])
            ->assertNotFound();
    }

    public function test_rechaza_divisa_inexistente(): void
    {
        $product = Product::factory()->create();

        $this->postJson("/api/products/{$product->id}/prices", ['currency_id' => 9999, 'price' => 10])
            ->assertStatus(422)
            ->assertJsonValidationErrors('currency_id');
    }

    public function test_rechaza_divisa_repetida(): void
    {
        $price = ProductPrice::factory()->create();

        $this->postJson("/api/products/{$price->product_id}/prices", [
            'currency_id' => $price->currency_id,
            'price' => 10,
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('currency_id');
    }

    public function test_rechaza_la_divisa_base_del_producto(): void
    {
        $product = Product::factory()->create();

        $this->postJson("/api/products/{$product->id}/prices", [
            'currency_id' => $product->currency_id,
            'price' => 10,
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('currency_id');
    }

    public function test_rechaza_importe_invalido(): void
    {
        $product = Product::factory()->create();
        $currency = Currency::factory()->create();

        $this->postJson("/api/products/{$product->id}/prices", ['currency_id' => $currency->id, 'price' => 'abc'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('price');

        $this->postJson("/api/products/{$product->id}/prices", ['currency_id' => $currency->id, 'price' => -5])
            ->assertStatus(422)
            ->assertJsonValidationErrors('price');
    }
}
