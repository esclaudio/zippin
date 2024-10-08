<?php

use App\Models\Product;

use function Pest\Laravel\getJson;

it('can fetch a list of products', function () {
    Product::factory()->count(5)->create();

    getJson('/api/v1/products')
        ->assertOk()
        ->assertJsonStructure(['data', 'links', 'meta'])
        ->assertJsonCount(5, 'data');
});

it('can search products', function () {
    Product::factory()->create([
        'name' => 'Product 1',
    ]);

    getJson('/api/v1/products?search=product')
        ->assertOk()
        ->assertJsonStructure(['data', 'links', 'meta'])
        ->assertJsonCount(1, 'data');
});

it('can fetch a single product', function () {
    $product = Product::factory()->create();

    getJson("/api/v1/products/{$product->slug}")
        ->assertOk()
        ->assertJson([
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
            ],
        ]);
});
