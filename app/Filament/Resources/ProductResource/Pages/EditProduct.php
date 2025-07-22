<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Produk')
                ->modalHeading('Hapus Produk')
                ->modalDescription('Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->modalCancelActionLabel('Batal'),
            
            Actions\RestoreAction::make()
                ->label('Pulihkan Produk')
                ->modalHeading('Pulihkan Produk')
                ->modalDescription('Apakah Anda yakin ingin memulihkan produk ini?')
                ->modalSubmitActionLabel('Ya, Pulihkan')
                ->modalCancelActionLabel('Batal'),

            Actions\Action::make('adjustStock')
                ->label('Sesuaikan Stok')
                ->form([
                    \Filament\Forms\Components\TextInput::make('quantity')
                        ->label('Jumlah')
                        ->numeric()
                        ->required(),
                    \Filament\Forms\Components\Select::make('type')
                        ->label('Tipe')
                        ->options([
                            'in' => 'Masuk',
                            'out' => 'Keluar',
                        ])
                        ->required(),
                    \Filament\Forms\Components\Textarea::make('notes')
                        ->label('Catatan')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->record->adjustStock(
                        quantity: $data['quantity'],
                        type: $data['type'],
                        notes: $data['notes']
                    );

                    Notification::make()
                        ->success()
                        ->title('Stok berhasil disesuaikan')
                        ->send();

                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record]));
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Produk berhasil diperbarui';
    }

    protected function getDeletedNotificationTitle(): ?string
    {
        return 'Produk berhasil dihapus';
    }

    protected function getRestoredNotificationTitle(): ?string
    {
        return 'Produk berhasil dipulihkan';
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Remove stock from data to prevent modification through edit form
        unset($data['stock']);

        // Ensure base_price is not negative
        if (isset($data['base_price']) && $data['base_price'] < 0) {
            $data['base_price'] = 0;
        }

        return $data;
    }
}
