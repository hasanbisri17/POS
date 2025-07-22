<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'name',
        'price_adjustment',
        'is_active',
    ];

    protected $casts = [
        'price_adjustment' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Methods
    public function getFinalPrice(): float
    {
        return $this->product->base_price + $this->price_adjustment;
    }

    public function getTotalSales(): int
    {
        return $this->transactionItems()
            ->whereHas('transaction', function ($query) {
                $query->where('status', 'completed');
            })
            ->sum('quantity');
    }

    public function getTotalRevenue(): float
    {
        return $this->transactionItems()
            ->whereHas('transaction', function ($query) {
                $query->where('status', 'completed');
            })
            ->sum('subtotal');
    }

    public function getFormattedPriceAdjustment(): string
    {
        $sign = $this->price_adjustment >= 0 ? '+' : '-';
        return "{$sign}Rp " . number_format(abs($this->price_adjustment), 0, ',', '.');
    }
}
