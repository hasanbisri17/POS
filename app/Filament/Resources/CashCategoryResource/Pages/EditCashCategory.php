<?php

namespace App\Filament\Resources\CashCategoryResource\Pages;

use App\Filament\Resources\CashCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCashCategory extends EditRecord
{
    protected static string $resource = CashCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Kategori')
                ->modalHeading('Hapus Kategori Kas')
                ->modalDescription('Apakah Anda yakin ingin menghapus kategori kas ini? Tindakan ini tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->modalCancelActionLabel('Batal')
                ->hidden(fn ($record) => $record->is_system),
            
            Actions\RestoreAction::make()
                ->label('Pulihkan Kategori')
                ->modalHeading('Pulihkan Kategori Kas')
                ->modalDescription('Apakah Anda yakin ingin memulihkan kategori kas ini?')
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
        return 'Kategori kas berhasil diperbarui';
    }

    protected function getDeletedNotificationTitle(): ?string
    {
        return 'Kategori kas berhasil dihapus';
    }

    protected function getRestoredNotificationTitle(): ?string
    {
        return 'Kategori kas berhasil dipulihkan';
    }

    protected function beforeSave(): void
    {
        // Prevent changing system category type
        if ($this->record->is_system && $this->data['type'] !== $this->record->type) {
            $this->halt();
            $this->notify('danger', 'Tidak dapat mengubah tipe kategori sistem');
            $this->data['type'] = $this->record->type;
        }
    }

    protected function beforeDelete(): void
    {
        // Prevent deletion of system categories
        if ($this->record->is_system) {
            $this->halt();
            $this->notify('danger', 'Tidak dapat menghapus kategori sistem');
        }

        // Prevent deletion if category has transactions
        if ($this->record->transactions()->exists()) {
            $this->halt();
            $this->notify('danger', 'Tidak dapat menghapus kategori yang memiliki transaksi');
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Ensure is_system value is preserved
        $data['is_system'] = $this->record->is_system;

        return $data;
    }
}
