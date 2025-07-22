<?php

namespace App\Filament\Resources\PaymentMethodResource\Pages;

use App\Filament\Resources\PaymentMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreatePaymentMethod extends CreateRecord
{
    protected static string $resource = PaymentMethodResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Metode pembayaran berhasil dibuat';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Auto-generate code from name if not provided
        if (empty($data['code'])) {
            $data['code'] = Str::slug($data['name']);
        }

        // Ensure code is unique
        $originalCode = $data['code'];
        $counter = 1;

        while ($this->getModel()::where('code', $data['code'])->exists()) {
            $data['code'] = $originalCode . '-' . $counter;
            $counter++;
        }

        return $data;
    }
}
