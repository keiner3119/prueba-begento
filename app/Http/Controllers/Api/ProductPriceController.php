<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductPriceRequest;
use App\Http\Resources\ProductPriceResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductPriceController extends Controller
{
    public function index(Product $product)
    {
        $prices = $product->prices()->with('currency')->orderBy('id')->get();

        return ProductPriceResource::collection($prices);
    }

    // Registra el precio del producto en otra divisa
    public function store(StoreProductPriceRequest $request, Product $product): JsonResponse
    {
        $price = $product->prices()->create($request->validated());

        return (new ProductPriceResource($price->load('currency')))
            ->response()
            ->setStatusCode(201);
    }
}
