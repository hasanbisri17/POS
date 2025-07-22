<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'config',
        'is_active',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCode($query, string $code)
    {
        return $query->where('code', $code);
    }

    // Methods
    public function getTotalTransactions(): int
    {
        return $this->transactions()
            ->where('status', 'completed')
            ->count();
    }

    public function getTotalAmount(): float
    {
        return $this->transactions()
            ->where('status', 'completed')
            ->sum('total_amount');
    }

    public function getConfigValue(string $key, $default = null)
    {
        return data_get($this->config, $key, $default);
    }

    public function setConfigValue(string $key, $value): void
    {
        $config = $this->config ?? [];
        data_set($config, $key, $value);
        $this->config = $config;
    }

    public function isCash(): bool
    {
        return $this->code === 'cash';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($paymentMethod) {
            if (empty($paymentMethod->code)) {
                $paymentMethod->code = str($paymentMethod->name)
                    ->slug()
                    ->toString();
            }
        });
    }
}
