<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Pages\Pos;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration(false)
            ->profile()
            ->authGuard('web')
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                'panels::auth.login.form.before',
                fn () => view('components.auth-header', [
                    'title' => 'POS Teh Poci',
                    'description' => 'Selamat datang di sistem POS Teh Poci'
                ])
            )
            ->colors([
                'primary' => Color::Amber,
            ])
            ->resources([
                \App\Filament\Resources\TransactionResource::class,
                \App\Filament\Resources\ProductResource::class,
                \App\Filament\Resources\CategoryResource::class,
                \App\Filament\Resources\PaymentMethodResource::class,
                \App\Filament\Resources\CashTransactionResource::class,
                \App\Filament\Resources\CashCategoryResource::class,
                \App\Filament\Resources\StockMovementResource::class,
                \App\Filament\Resources\UserResource::class,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                'Penjualan',
                'Master Data',
                'Keuangan',
                'Pengaturan',
            ])
            ->pages([
                \App\Filament\Pages\Dashboard::class,
                \App\Filament\Pages\Pos::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                \App\Filament\Widgets\LowStockProducts::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->topNavigation()
            ->brandName(' ')
            ->favicon(asset('storage/logo-teh-poci.png'))
            ->maxContentWidth('full');
    }
}
