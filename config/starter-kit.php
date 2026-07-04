<?php

return [
    'locale' => [
        'default' => env('APP_LOCALE', 'en'),
        'locales' => ['en', 'ar'],
        'fallback' => env('APP_FALLBACK_LOCALE', 'en'),
    ],

    'user' => [
        'default_locale' => env('STARTER_USER_DEFAULT_LOCALE', 'en'),
        'default_timezone' => env('STARTER_USER_DEFAULT_TIMEZONE', 'UTC'),
        'default_is_active' => true,
    ],

    'filament' => [
        'path' => env('FILAMENT_PATH', 'admin'),
        'domain' => env('FILAMENT_DOMAIN', null),
        'auth_guard' => 'web',
        'pages' => [
            'dashboard' => \Elmasry\StarterKit\Filament\Pages\Dashboard::class,
        ],
        'resources' => [
            \Elmasry\StarterKit\Filament\Resources\UserResource::class,
            \Elmasry\StarterKit\Filament\Resources\RoleResource::class,
            \Elmasry\StarterKit\Filament\Resources\PermissionResource::class,
            \Elmasry\StarterKit\Filament\Resources\SettingsResource::class,
            \Elmasry\StarterKit\Filament\Resources\PageResource::class,
            \Elmasry\StarterKit\Filament\Resources\TranslationResource::class,
        ],
        'widgets' => [
            \Elmasry\StarterKit\Filament\Widgets\StatsOverviewWidget::class,
            \Elmasry\StarterKit\Filament\Widgets\LatestUsersWidget::class,
            \Elmasry\StarterKit\Filament\Widgets\RecentActivityWidget::class,
        ],
    ],

    'seo' => [
        'default_title' => env('APP_NAME', 'Laravel'),
        'default_description' => '',
        'og_image' => '',
    ],

    'contact' => [
        'notification_email' => env('CONTACT_NOTIFICATION_EMAIL', 'admin@example.com'),
    ],

    'media' => [
        'disk' => env('MEDIA_DISK', 'public'),
        'collection_names' => [
            'pages' => 'pages',
            'settings' => 'settings',
            'avatars' => 'avatars',
            'gallery' => 'gallery',
        ],
    ],

    'cache' => [
        'settings_ttl' => 3600,
        'translations_ttl' => 3600,
    ],
];
