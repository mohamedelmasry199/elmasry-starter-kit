<?php

namespace Elmasry\StarterKit\Filament\Widgets;

use Elmasry\StarterKit\Models\User;
use Elmasry\StarterKit\Models\Page;
use Elmasry\StarterKit\Models\Contact;
use Elmasry\StarterKit\Models\Category;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\TableWidget as BaseTableWidget;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-o-users')
                ->color('info'),
            Stat::make('Total Pages', Page::count())
                ->description('Published pages')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('success'),
            Stat::make('Total Contacts', Contact::count())
                ->description('Contact submissions')
                ->descriptionIcon('heroicon-o-inbox')
                ->color('warning'),
            Stat::make('Total Categories', Category::count())
                ->description('Content categories')
                ->descriptionIcon('heroicon-o-tag')
                ->color('gray'),
        ];
    }
}

class LatestUsers extends BaseTableWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->latest()->limit(10))
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ]);
    }
}

class RecentActivity extends BaseTableWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(DB::table('activity_log')->latest()->limit(10))
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Action')
                    ->limit(50),
                Tables\Columns\TextColumn::make('causer_id')
                    ->label('User')
                    ->formatStateUsing(fn ($state) => optional(User::find($state))->name ?? 'System'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime(),
            ]);
    }
}
