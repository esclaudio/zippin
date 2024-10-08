<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\postJson;

it('can login', function () {
    $user = User::factory()->create();

    postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ])
        ->assertOk()
        ->assertJson(function (AssertableJson $json) {
            $json->has('access_token');
            $json->has('token_type');
            $json->has('expires_in');
        });
});

it('can refresh token', function () {
    $user = User::factory()->create();

    $this->actingAsJwtUser($user)
        ->postJson('api/v1/auth/refresh')
        ->assertOk()
        ->assertJson(function (AssertableJson $json) {
            $json->has('access_token');
            $json->has('token_type');
            $json->has('expires_in');
        });
});
