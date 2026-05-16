<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingZone extends Model
{
    protected $fillable = ['name', 'regions', 'is_active'];

    protected $casts = [
        'regions' => 'array',
        'is_active' => 'boolean',
    ];

    public function methods(): HasMany
    {
        return $this->hasMany(ShippingMethod::class);
    }
}
