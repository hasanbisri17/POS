<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'base_price',
        'image',
        'stock',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
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

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stock', '<=', $threshold);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    // Methods
    public function getImageUrlAttribute(): ?string
    {
        return $this->image
            ? Storage::disk('public')->url($this->image)
            : null;
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

    public function getStockValue(): float
    {
        return $this->stock * $this->base_price;
    }

    public function adjustStock(int $quantity, string $type = 'in', ?string $notes = null, ?int $transactionId = null): void
    {
        if ($type === 'out') {
            $quantity = -abs($quantity);
        } else {
            $quantity = abs($quantity);
        }

        $this->stockMovements()->create([
            'quantity' => abs($quantity),
            'type' => $type,
            'notes' => $notes,
            'created_by' => auth()->id(),
            'transaction_id' => $transactionId,
        ]);

        $this->increment('stock', $quantity);
    }

    public function hasVariant(int $variantId): bool
    {
        return $this->variants()->where('id', $variantId)->exists();
    }

    public function getVariantPrice(int $variantId): float
    {
        $variant = $this->variants()->find($variantId);
        return $variant 
            ? $this->base_price + $variant->price_adjustment
            : $this->base_price;
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {
            if ($product->isForceDeleting()) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('image') && $product->getOriginal('image')) {
                Storage::disk('public')->delete($product->getOriginal('image'));
            }
        });
    }
}
