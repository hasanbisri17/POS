<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LowStockProducts extends BaseWidget
{
    protected static ?string $heading = 'Produk Stok Menipis & Habis';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->where(function (Builder $query) {
                        $query->where('stock', '<=', 10)
                            ->where('stock', '>=', 0)
                            ->orWhere('stock', '<', 0);
                    })
                    ->orderBy('stock', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 0 => 'danger',
                        $state <= 5 => 'warning',
                        $state <= 10 => 'info',
                        default => 'success',
                    })
                    ->description(fn (Product $record): string => match (true) {
                        $record->stock <= 0 => 'Stok Habis',
                        $record->stock <= 5 => 'Stok Sangat Rendah',
                        $record->stock <= 10 => 'Stok Rendah',
                        default => '',
                    }),
                Tables\Columns\TextColumn::make('base_price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->defaultSort('stock', 'asc')
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Edit Stok')
                    ->url(fn (Product $record): string => route('filament.admin.resources.products.edit', $record))
                    ->icon('heroicon-m-pencil-square')
                    ->color('warning'),
            ])
            ->paginated([5, 10, 25, 50])
            ->defaultPaginationPageOption(5);
    }
}
