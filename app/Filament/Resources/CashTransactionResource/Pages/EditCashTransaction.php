<?php

namespace App\Filament\Resources\CashTransactionResource\Pages;

use App\Filament\Resources\CashTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCashTransaction extends EditRecord
{
    protected static string $resource = CashTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Transaksi')
                ->modalHeading('Hapus Transaksi Kas')
                ->modalDescription('Apakah Anda yakin ingin menghapus transaksi kas ini? Tindakan ini tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->modalCancelActionLabel('Batal')
                ->hidden(fn ($record) => $record->transaction_id !== null),
            
            Actions\RestoreAction::make()
                ->label('Pulihkan Transaksi')
                ->modalHeading('Pulihkan Transaksi Kas')
                ->modalDescription('Apakah Anda yakin ingin memulihkan transaksi kas ini?')
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
        return 'Transaksi kas berhasil diperbarui';
    }

    protected function getDeletedNotificationTitle(): ?string
    {
        return 'Transaksi kas berhasil dihapus';
    }

    protected function getRestoredNotificationTitle(): ?string
    {
        return 'Transaksi kas berhasil dipulihkan';
    }

    protected function beforeSave(): void
    {
        // Prevent editing transactions from sales
        if ($this->record->transaction_id !== null) {
            $this->halt();
            $this->notify('danger', 'Tidak dapat mengubah transaksi kas dari penjualan');
        }

        // Ensure type matches category type
        $category = \App\Models\CashCategory::find($this->data['cash_category_id']);
        if ($category) {
            $this->data['type'] = $category->type;
        }
    }

    protected function beforeDelete(): void
    {
        // Prevent deleting transactions from sales
        if ($this->record->transaction_id !== null) {
            $this->halt();
            $this->notify('danger', 'Tidak dapat menghapus transaksi kas dari penjualan');
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Ensure created_by value is preserved
        $data['created_by'] = $this->record->created_by;

        return $data;
    }
}
