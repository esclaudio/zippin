<?php

use App\Models\User;

it('can fetch a list of addresses', function () {
    $user = User::factory()->hasAddresses(5)->create();

    $this->actingAsJwtUser($user)
        ->getJson('/api/v1/addresses')
        ->assertOk()
        ->assertJsonStructure(['data', 'links', 'meta'])
        ->assertJsonCount(5, 'data');
});
