<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasHashIdRouting
{
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        if ($field !== null) {
            return parent::resolveRouteBinding($value, $field);
        }

        return $this->byHashId($value)->firstOrFail();
    }

    public function getRouteKey(): string
    {
        return $this->hashId;
    }
}
