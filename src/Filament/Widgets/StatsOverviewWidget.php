<?php

namespace Elmasry\StarterKit\Filament\Widgets;

use Elmasry\StarterKit\Models\User;
use Elmasry\StarterKit\Models\Page;
use Elmasry\StarterKit\Models\Contact;
use Elmasry\StarterKit\Models\Category;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
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
