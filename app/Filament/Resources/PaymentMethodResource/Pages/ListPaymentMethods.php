<?php

namespace App\Filament\Resources\PaymentMethodResource\Pages;

use App\Filament\Resources\PaymentMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPaymentMethods extends ListRecords
{
    protected static string $resource = PaymentMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Metode Pembayaran'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Metode')
                ->badge($this->getModel()::count()),
            
            'active' => Tab::make('Aktif')
                ->badge($this->getModel()::where('is_active', true)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true)),
            
            'inactive' => Tab::make('Nonaktif')
                ->badge($this->getModel()::where('is_active', false)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', false)),
            
            'trashed' => Tab::make('Terhapus')
                ->badge($this->getModel()::onlyTrashed()->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed()),
        ];
    }
}
