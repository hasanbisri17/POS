<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Produk'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Produk')
                ->badge($this->getModel()::count()),
            
            'active' => Tab::make('Aktif')
                ->badge($this->getModel()::where('is_active', true)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true)),
            
            'inactive' => Tab::make('Nonaktif')
                ->badge($this->getModel()::where('is_active', false)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', false)),
            
            'low_stock' => Tab::make('Stok Menipis')
                ->badge($this->getModel()::where('stock', '<=', 10)->where('stock', '>', 0)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('stock', '<=', 10)->where('stock', '>', 0)),
            
            'out_of_stock' => Tab::make('Stok Habis')
                ->badge($this->getModel()::where('stock', '<=', 0)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('stock', '<=', 0)),
            
            'trashed' => Tab::make('Terhapus')
                ->badge($this->getModel()::onlyTrashed()->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed()),
        ];
    }
}
