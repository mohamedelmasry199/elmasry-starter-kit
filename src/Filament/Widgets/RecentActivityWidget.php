<?php

namespace Elmasry\StarterKit\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentActivityWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                \DB::table('activity_log')
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Action')
                    ->limit(50),
                Tables\Columns\TextColumn::make('causer_id')
                    ->label('User')
                    ->formatStateUsing(fn ($state) => optional(\Elmasry\StarterKit\Models\User::find($state))->name ?? 'System'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime(),
            ]);
    }
}
