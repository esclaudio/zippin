<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->filter($request->input('search'))
            ->orderBy('id')
            ->paginate();

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }
}
