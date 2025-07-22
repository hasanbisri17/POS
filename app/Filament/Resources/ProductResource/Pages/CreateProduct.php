<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Produk berhasil dibuat';
    }

    protected function beforeCreate(): void
    {
        // Validate that stock is not negative
        $data = $this->data;
        if (isset($data['stock']) && $data['stock'] < 0) {
            $this->data['stock'] = 0;
        }

        // Ensure base_price is not negative
        if (isset($data['base_price']) && $data['base_price'] < 0) {
            $this->data['base_price'] = 0;
        }
    }

    protected function afterCreate(): void
    {
        $product = $this->record;

        // Create stock movement record for initial stock
        if ($product->stock > 0) {
            $product->stockMovements()->create([
                'quantity' => $product->stock,
                'type' => 'in',
                'notes' => 'Stok awal produk',
                'created_by' => auth()->id(),
            ]);
        }
    }
}
