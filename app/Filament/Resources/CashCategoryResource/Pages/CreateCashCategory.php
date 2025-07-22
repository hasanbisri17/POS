<?php

namespace App\Filament\Resources\CashCategoryResource\Pages;

use App\Filament\Resources\CashCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCashCategory extends CreateRecord
{
    protected static string $resource = CashCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Kategori kas berhasil dibuat';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure is_system is always false for user-created categories
        $data['is_system'] = false;

        return $data;
    }

    protected function beforeCreate(): void
    {
        // Additional validation if needed
        // For example, prevent creating duplicate system categories
        if ($this->data['is_system'] ?? false) {
            $this->halt();
            $this->notify('danger', 'Tidak dapat membuat kategori sistem secara manual');
        }
    }
}
