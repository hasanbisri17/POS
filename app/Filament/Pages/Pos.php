<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Pos extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationLabel = 'POS';

    protected static ?string $title = 'Point of Sale';

    protected static ?string $slug = 'pos';

    protected static ?string $navigationGroup = 'Penjualan';

    protected static ?int $navigationSort = -2;

    protected static string $view = 'filament.pages.pos';
}
