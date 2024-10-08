<?php

namespace App\Models;

use App\Models\Builders\ProductQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $price
 * @property int $stock
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static ProductQueryBuilder|Product filter(?string $value)
 * @method static ProductQueryBuilder|Product newModelQuery()
 * @method static ProductQueryBuilder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product onlyTrashed()
 * @method static ProductQueryBuilder|Product query()
 * @method static ProductQueryBuilder|Product whereCreatedAt($value)
 * @method static ProductQueryBuilder|Product whereDeletedAt($value)
 * @method static ProductQueryBuilder|Product whereDescription($value)
 * @method static ProductQueryBuilder|Product whereId($value)
 * @method static ProductQueryBuilder|Product whereName($value)
 * @method static ProductQueryBuilder|Product wherePrice($value)
 * @method static ProductQueryBuilder|Product whereSlug($value)
 * @method static ProductQueryBuilder|Product whereStock($value)
 * @method static ProductQueryBuilder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'integer',
        ];
    }

    public function newEloquentBuilder($query): ProductQueryBuilder
    {
        return new ProductQueryBuilder($query);
    }
}
