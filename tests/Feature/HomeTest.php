<?php

use function Pest\Laravel\getJson;

it('returns a successful response', function () {
    getJson('/api/v1')
        ->assertOk();
});
