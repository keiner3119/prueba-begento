<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;

// Base para las pruebas de la API: BD limpia y API key en cada llamada
abstract class ApiTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeaders(['X-API-Key' => 'clave-de-prueba', 'Accept' => 'application/json']);
    }
}
