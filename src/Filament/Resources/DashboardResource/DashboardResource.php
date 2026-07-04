<?php

namespace Elmasry\StarterKit\Filament\Resources;

use Elmasry\StarterKit\Filament\Resources\DashboardResource\Pages;
use Elmasry\StarterKit\Models\User;
use Filament\Resources\Resource;

class DashboardResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationGroup = 'Dashboard';

    protected static ?int $navigationSort = 1;

    public static function getPages(): array
    {
        return [
            'index' => Pages\Dashboard::route('/'),
        ];
    }
}
