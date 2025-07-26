<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Manajemen User';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return auth()->user()->can('manage_users');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('manage_users');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('manage_users');
    }

    public static function canDelete(Model $record): bool
    {
        // Prevent deleting own account
        if (auth()->id() === $record->id) {
            return false;
        }
        return auth()->user()->can('manage_users');
    }

    /**
     * Check if user has related data that prevents deletion
     */
    protected static function checkUserRelatedData(User $user): array
    {
        $relatedData = [];

        // Check cash transactions
        $cashTransactionsCount = $user->cashTransactions()->count();
        if ($cashTransactionsCount > 0) {
            $relatedData[] = "Transaksi Kas: {$cashTransactionsCount} record";
        }

        // Check transactions
        $transactionsCount = $user->transactions()->count();
        if ($transactionsCount > 0) {
            $relatedData[] = "Transaksi Penjualan: {$transactionsCount} record";
        }

        // Check stock movements
        $stockMovementsCount = $user->stockMovements()->count();
        if ($stockMovementsCount > 0) {
            $relatedData[] = "Pergerakan Stok: {$stockMovementsCount} record";
        }

        return $relatedData;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => $state ? Hash::make($state) : null)
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->maxLength(255)
                            ->hiddenOn('view'),
                        Forms\Components\Select::make('roles')
                            ->label('Role')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload()
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->colors([
                        'primary' => 'Admin',
                        'success' => 'Kasir',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->label('Filter Role')
                    ->relationship('roles', 'name')
                    ->preload()
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->modalHeading('Edit User'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus User')
                    ->modalDescription('Apakah Anda yakin ingin menghapus user ini?')
                    ->before(function (Tables\Actions\DeleteAction $action, User $record) {
                        // Check if user has related data
                        $relatedData = static::checkUserRelatedData($record);
                        
                        if (!empty($relatedData)) {
                            // Show notification with detailed reason
                            $message = "User '{$record->name}' tidak dapat dihapus karena masih memiliki data terkait:\n\n" . 
                                      implode("\n", array_map(fn($item) => "• {$item}", $relatedData)) . 
                                      "\n\nHapus atau transfer data terkait terlebih dahulu sebelum menghapus user ini.";
                            
                            Notification::make()
                                ->title('Tidak Dapat Menghapus User')
                                ->body($message)
                                ->danger()
                                ->persistent()
                                ->send();
                            
                            // Cancel the delete action
                            $action->cancel();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus User Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus user yang dipilih?')
                        ->before(function (Tables\Actions\DeleteBulkAction $action, $records) {
                            foreach ($records as $record) {
                                $relatedData = static::checkUserRelatedData($record);
                                
                                if (!empty($relatedData)) {
                                    $message = "User '{$record->name}' tidak dapat dihapus karena masih memiliki data terkait:\n\n" . 
                                              implode("\n", array_map(fn($item) => "• {$item}", $relatedData)) . 
                                              "\n\nHapus atau transfer data terkait terlebih dahulu.";
                                    
                                    Notification::make()
                                        ->title('Tidak Dapat Menghapus User')
                                        ->body($message)
                                        ->danger()
                                        ->persistent()
                                        ->send();
                                    
                                    $action->cancel();
                                    return;
                                }
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'User';
    }

    public static function getPluralModelLabel(): string
    {
        return 'User';
    }

    protected static ?string $recordTitleAttribute = 'name';
}
