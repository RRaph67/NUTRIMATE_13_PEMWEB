<?php

namespace App\Models;

use Database\Factories\FoodFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'name',
    'category',
    'price',
    'portion_description',
    'description',
    'calories',
    'protein',
    'carbs',
    'fat',
    'image_url',
])]
class Food extends Model
{
    /** @use HasFactory<FoodFactory> */
    use HasFactory;

    /**
     * The primary key associated with the table.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The table associated with the model.
     */
    protected $table = 'foods';

    /**
     * Allowed food categories (must match StoreFoodRequest / UpdateFoodRequest rules).
     *
     * @var list<string>
     */
    public const CATEGORIES = [
        'Karbohidrat',
        'Protein',
        'Sayuran',
        'Buah',
        'Minuman',
        'Lainnya',
    ];

    /**
     * Whitelisted sort keys. User input is only ever used as a key into this
     * map, never as a column name.
     *
     * @var array<string, array{column: string, direction: string}>
     */
    public const SORTS = [
        'termurah' => ['column' => 'price', 'direction' => 'asc'],
        'termahal' => ['column' => 'price', 'direction' => 'desc'],
        'kalori' => ['column' => 'calories', 'direction' => 'desc'],
        'cheap' => ['column' => 'price', 'direction' => 'asc'],
        'cal' => ['column' => 'calories', 'direction' => 'asc'],
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'calories' => 'decimal:2',
            'protein' => 'decimal:2',
            'carbs' => 'decimal:2',
            'fat' => 'decimal:2',
            'description' => 'string',
            'image_url' => 'string',
        ];
    }

    /**
     * Apply the catalogue search, category filter and whitelisted sorting.
     *
     * @param  array{q?: string|null, category?: string|null, sort?: string|null}  $filters
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        $keyword = $filters['q'] ?? null;

        if (filled($keyword)) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%'.mb_strtolower(trim($keyword)).'%']);
        }

        if (filled($filters['category'] ?? null)) {
            $query->where('category', $filters['category']);
        }

        $sort = self::SORTS[$filters['sort'] ?? ''] ?? null;

        if ($sort !== null) {
            $query->orderBy($sort['column'], $sort['direction']);
        } else {
            $query->orderBy('name');
        }

        return $query;
    }

    /**
     * Generate a UUID primary key before inserting a new record.
     */
    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            if ($model->id === null || $model->id === '') {
                $model->id = (string) Str::uuid();
            }
        });
    }
}
