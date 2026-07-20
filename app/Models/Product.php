<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use App\Notifications\Admin\LowStockAlert;
use App\Notifications\Admin\ProductUnavailable;
use App\Services\AdminNotifier;


class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'sku',
        'price',
        'compare_price',
        'stock',
        'low_stock_threshold',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }
    protected static function booted(): void
    {
        static::updated(function (Product $product) {
            if ($product->wasChanged('stock')) {
                if ($product->stock === 0) {
                    AdminNotifier::send(new ProductUnavailable($product));
                } elseif ($product->isLowStock()) {
                    AdminNotifier::send(new LowStockAlert($product));
                }
            }

            if ($product->wasChanged('is_active') && ! $product->is_active) {
                AdminNotifier::send(new ProductUnavailable($product));
            }
        });
    }

    // ---- Scopes ----

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock(Builder $query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeFeatured(Builder $query)
    {
        return $query->where('is_featured', true);
    }

    // ---- Helpers ----

    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= $this->low_stock_threshold;
    }

    public function isPurchasable(): bool
    {
        return $this->is_active && $this->stock > 0;
    }
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
    public function stockNotifications(): HasMany
    {
        return $this->hasMany(StockNotification::class);
    }

}
