<?php

namespace App\Filament\Exports;

use App\Models\Transaction;
use Filament\Actions\Exports\Models\Export;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Illuminate\Database\Eloquent\Builder;

class TransactionExporter extends Exporter
{
    protected static ?string $model = Transaction::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('created_at')
                ->label('Tanggal')
                ->formatStateUsing(fn ($state) => $state->format('d M Y H:i')),
            ExportColumn::make('invoice_number')
                ->label('No. Invoice'),
            ExportColumn::make('products')
                ->label('Produk')
                ->getStateUsing(fn (Transaction $record): string => 
                    $record->items->pluck('product.name')->join(', ')),
            ExportColumn::make('variants')
                ->label('Varian')
                ->getStateUsing(fn (Transaction $record): string => 
                    $record->items->map(fn ($item) => $item->variant?->name ?? '-')->join(', ')),
            ExportColumn::make('quantities')
                ->label('Jumlah')
                ->getStateUsing(fn (Transaction $record): string => 
                    $record->items->pluck('quantity')->join(', ')),
            ExportColumn::make('total_amount')
                ->label('Total')
                ->formatStateUsing(fn ($state) => 'IDR ' . number_format($state, 2)),
            ExportColumn::make('payment_method.name')
                ->label('Metode Pembayaran'),
            ExportColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'pending' => 'Pending',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                }),
            ExportColumn::make('createdBy.name')
                ->label('Dibuat Oleh'),
        ];
    }

    public function getCompletedNotificationBody(): string
    {
        return 'Ekspor transaksi telah selesai dan siap diunduh.';
    }

    protected function getExportFilename(): string
    {
        return 'transaksi-' . now()->format('Y-m-d') . '.xlsx';
    }
}
