<?php

namespace App\Models\Traits;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasHashId
{
    public function hashId(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getHashIdGenerator()->encode($this->getKey())
        );
    }

    public function scopeByHashId(Builder $query, string $hashId): void
    {
        $query->where('id', $this->keyFromHashId($hashId));
    }

    protected function keyFromHashId(string $hashId): string
    {
        return $this->getHashIdGenerator()->decode($hashId)[0];
    }

    protected function getHashIdGenerator(): Hashids
    {
        return new Hashids(config('hashids.salt'), 8, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
    }
}
