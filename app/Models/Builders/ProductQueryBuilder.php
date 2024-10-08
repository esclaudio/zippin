<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilder extends Builder
{
    public function filter(?string $value): self
    {
        return $this->when(
            $value,
            fn (ProductQueryBuilder $query) => $query->whereAny(['name', 'description'], 'like', "%{$value}%")
        );
    }
}
