<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('begento.api_key');
        $sent = (string) $request->header('X-API-Key');

        // Sin clave configurada o con clave distinta se rechaza
        if ($expected === '' || ! hash_equals($expected, $sent)) {
            return response()->json(['message' => 'API key inválida o ausente.'], 401);
        }

        return $next($request);
    }
}
