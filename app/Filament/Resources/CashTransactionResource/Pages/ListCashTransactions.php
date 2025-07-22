<?php

namespace App\Filament\Resources\CashTransactionResource\Pages;

use App\Filament\Resources\CashTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListCashTransactions extends ListRecords
{
    protected static string $resource = CashTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Transaksi'),
        ];
    }

    public function getTabs(): array
    {
        $model = $this->getModel();

        $today = now()->format('Y-m-d');
        $thisMonth = now()->format('Y-m');

        return [
            'all' => Tab::make('Semua Transaksi')
                ->badge($model::count()),
            
            'income' => Tab::make('Pemasukan')
                ->badge($model::where('type', 'income')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'income')),
            
            'expense' => Tab::make('Pengeluaran')
                ->badge($model::where('type', 'expense')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'expense')),
            
            'today' => Tab::make('Hari Ini')
                ->badge($model::whereDate('transaction_date', $today)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDate('transaction_date', $today)),
            
            'this_month' => Tab::make('Bulan Ini')
                ->badge($model::whereYear('transaction_date', now()->year)
                    ->whereMonth('transaction_date', now()->month)
                    ->count())
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereYear('transaction_date', now()->year)
                    ->whereMonth('transaction_date', now()->month)),
            
            'from_sales' => Tab::make('Dari Penjualan')
                ->badge($model::whereNotNull('transaction_id')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('transaction_id')),
            
            'non_sales' => Tab::make('Non-Penjualan')
                ->badge($model::whereNull('transaction_id')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('transaction_id')),
            
            'trashed' => Tab::make('Terhapus')
                ->badge($model::onlyTrashed()->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed()),
        ];
    }

}
