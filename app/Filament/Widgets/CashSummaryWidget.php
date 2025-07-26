<?php

namespace App\Filament\Widgets;

use App\Models\CashTransaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CashSummaryWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $totalIncome = CashTransaction::where('type', 'income')->sum('amount');
        $totalExpense = CashTransaction::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        return [
            Stat::make('Total Pemasukan', 'Rp ' . number_format($totalIncome, 0, ',', '.'))
                ->description('Total pemasukan kas')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Total Pengeluaran', 'Rp ' . number_format($totalExpense, 0, ',', '.'))
                ->description('Total pengeluaran kas')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger')
                ->icon('heroicon-o-credit-card'),

            Stat::make('Saldo Kas', 'Rp ' . number_format($balance, 0, ',', '.'))
                ->description('Saldo kas saat ini')
                ->descriptionIcon($balance >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($balance >= 0 ? 'primary' : 'warning')
                ->icon('heroicon-o-scale'),
        ];
    }
}
