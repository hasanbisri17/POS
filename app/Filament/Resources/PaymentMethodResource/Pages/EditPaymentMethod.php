<?php

namespace App\Filament\Resources\PaymentMethodResource\Pages;

use App\Filament\Resources\PaymentMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditPaymentMethod extends EditRecord
{
    protected static string $resource = PaymentMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Metode')
                ->modalHeading('Hapus Metode Pembayaran')
                ->modalDescription('Apakah Anda yakin ingin menghapus metode pembayaran ini? Tindakan ini tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->modalCancelActionLabel('Batal'),
            
            Actions\RestoreAction::make()
                ->label('Pulihkan Metode')
                ->modalHeading('Pulihkan Metode Pembayaran')
                ->modalDescription('Apakah Anda yakin ingin memulihkan metode pembayaran ini?')
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
        return 'Metode pembayaran berhasil diperbarui';
    }

    protected function getDeletedNotificationTitle(): ?string
    {
        return 'Metode pembayaran berhasil dihapus';
    }

    protected function getRestoredNotificationTitle(): ?string
    {
        return 'Metode pembayaran berhasil dipulihkan';
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // If code is changed, ensure it's unique
        if (isset($data['code']) && $data['code'] !== $this->record->code) {
            $originalCode = $data['code'];
            $counter = 1;

            while ($this->getModel()::where('code', $data['code'])
                ->where('id', '!=', $this->record->id)
                ->exists()
            ) {
                $data['code'] = $originalCode . '-' . $counter;
                $counter++;
            }
        }

        return $data;
    }

    protected function beforeDelete(): void
    {
        // Prevent deletion of cash payment method
        if ($this->record->code === 'cash') {
            $this->halt();
            $this->notify('danger', 'Metode pembayaran tunai tidak dapat dihapus');
        }
    }
}
