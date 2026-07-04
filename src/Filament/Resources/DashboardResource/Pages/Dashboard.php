<?php

namespace Elmasry\StarterKit\Filament\Resources\DashboardResource\Pages;

use Elmasry\StarterKit\Filament\Resources\DashboardResource;
use Elmasry\StarterKit\Filament\Widgets\StatsOverviewWidget;
use Elmasry\StarterKit\Filament\Widgets\LatestUsersWidget;
use Elmasry\StarterKit\Filament\Widgets\RecentActivityWidget;
use Filament\Resources\Pages\Page;

class Dashboard extends Page
{
    protected static string $resource = DashboardResource::class;

    protected static string $view = 'filament::pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            LatestUsersWidget::class,
            RecentActivityWidget::class,
        ];
    }
}
