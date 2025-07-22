<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Methods
    public function getActiveProductsCount(): int
    {
        return $this->products()->active()->count();
    }

    public function getTotalProductStock(): int
    {
        return $this->products()->sum('stock');
    }

    public function getTotalProductValue(): float
    {
        return $this->products()
            ->selectRaw('SUM(base_price * stock) as total_value')
            ->value('total_value') ?? 0;
    }
}
