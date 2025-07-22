<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Kategori')
                ->modalHeading('Hapus Kategori')
                ->modalDescription('Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->modalCancelActionLabel('Batal'),
            
            Actions\RestoreAction::make()
                ->label('Pulihkan Kategori')
                ->modalHeading('Pulihkan Kategori')
                ->modalDescription('Apakah Anda yakin ingin memulihkan kategori ini?')
                ->modalSubmitActionLabel('Ya, Pulihkan')
                ->modalCancelActionLabel('Batal'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Kategori berhasil diperbarui';
    }

    protected function getDeletedNotificationTitle(): ?string
    {
        return 'Kategori berhasil dihapus';
    }

    protected function getRestoredNotificationTitle(): ?string
    {
        return 'Kategori berhasil dipulihkan';
    }
}
