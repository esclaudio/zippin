<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    public function actingAsJwtUser(JWTSubject $user)
    {
        $token = JWTAuth::fromUser($user);

        $this->withHeaders(['Authorization' => "Bearer {$token}"]);

        return $this;
    }
}
