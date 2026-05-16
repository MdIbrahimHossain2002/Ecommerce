<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'brand_id', 'name', 'slug', 'sku', 'barcode',
        'description', 'specifications', 'tags', 'regular_price', 'discount_price',
        'stock', 'status', 'seo_title', 'seo_description',
        'is_featured', 'is_bestseller', 'is_new_arrival', 'sold_count',
    ];

    protected $casts = [
        'specifications' => 'array',
        'tags' => 'array',
        'regular_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_bestseller' => 'boolean',
        'is_new_arrival' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->discount_price ?? $this->regular_price);
    }

    public function getPrimaryImageAttribute(): ?string
    {
        $image = $this->images->firstWhere('is_primary', true)
            ?? $this->images->first();

        return $image?->path;
    }
}
