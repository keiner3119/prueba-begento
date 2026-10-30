<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiKeyTest extends TestCase
{
    public function test_sin_api_key_responde_401(): void
    {
        $this->getJson('/api/products')->assertStatus(401);
    }

    public function test_con_api_key_incorrecta_responde_401(): void
    {
        $this->withHeader('X-API-Key', 'otra-clave')
            ->getJson('/api/products')
            ->assertStatus(401);
    }

    public function test_sin_clave_configurada_rechaza_todo(): void
    {
        config(['begento.api_key' => '']);

        $this->withHeader('X-API-Key', '')
            ->getJson('/api/products')
            ->assertStatus(401);
    }
}
