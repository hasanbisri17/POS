<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'type',
        'notes',
        'created_by',
        'transaction_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    // Scopes
    public function scopeIn($query)
    {
        return $query->where('type', 'in');
    }

    public function scopeOut($query)
    {
        return $query->where('type', 'out');
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('created_at', $date);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Methods
    public function getFormattedQuantity(): string
    {
        $sign = $this->type === 'in' ? '+' : '-';
        return "{$sign}{$this->quantity}";
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'in' => 'Masuk',
            'out' => 'Keluar',
            default => ucfirst($this->type),
        };
    }

    public function getTypeColor(): string
    {
        return match($this->type) {
            'in' => 'success',
            'out' => 'danger',
            default => 'primary',
        };
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($stockMovement) {
            if (empty($stockMovement->created_by)) {
                $stockMovement->created_by = auth()->id();
            }
        });
    }
}
