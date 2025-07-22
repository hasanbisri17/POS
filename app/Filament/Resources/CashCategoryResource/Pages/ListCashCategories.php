<?php

namespace App\Filament\Resources\CashCategoryResource\Pages;

use App\Filament\Resources\CashCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListCashCategories extends ListRecords
{
    protected static string $resource = CashCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Kategori'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Kategori')
                ->badge($this->getModel()::count()),
            
            'income' => Tab::make('Pemasukan')
                ->badge($this->getModel()::where('type', 'income')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'income')),
            
            'expense' => Tab::make('Pengeluaran')
                ->badge($this->getModel()::where('type', 'expense')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'expense')),
            
            'active' => Tab::make('Aktif')
                ->badge($this->getModel()::where('is_active', true)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true)),
            
            'inactive' => Tab::make('Nonaktif')
                ->badge($this->getModel()::where('is_active', false)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', false)),
            
            'system' => Tab::make('Sistem')
                ->badge($this->getModel()::where('is_system', true)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_system', true)),
            
            'trashed' => Tab::make('Terhapus')
                ->badge($this->getModel()::onlyTrashed()->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed()),
        ];
    }
}
