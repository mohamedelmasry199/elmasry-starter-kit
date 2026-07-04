<?php

namespace Elmasry\StarterKit\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class InstallStarterKitCommand extends Command
{
    protected $signature = 'starter-kit:install
        {--force : Force overwrite existing files}
        {--with-demo : Include demo data}';

    protected $description = 'Install the Elmasry Starter Kit package';

    public function handle(): int
    {
        $this->info('🚀 Installing Elmasry Starter Kit...');

        // Publish config
        $this->call('vendor:publish', [
            '--provider' => 'Elmasry\\StarterKit\\StarterKitServiceProvider',
            '--tag' => 'starter-kit-config',
            '--force' => $this->option('force'),
        ]);

        // Publish migrations
        $this->call('vendor:publish', [
            '--provider' => 'Elmasry\\StarterKit\\StarterKitServiceProvider',
            '--tag' => 'starter-kit-migrations',
            '--force' => $this->option('force'),
        ]);

        // Publish translations
        $this->call('vendor:publish', [
            '--provider' => 'Elmasry\\StarterKit\\StarterKitServiceProvider',
            '--tag' => 'starter-kit-lang',
            '--force' => $this->option('force'),
        ]);

        // Run migrations
        $this->call('migrate');

        // Install Spatie permissions
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\\Permission\\PermissionServiceProvider',
        ]);
        $this->call('migrate');

        // Create default roles and permissions
        $this->createDefaultRolesAndPermissions();

        // Create default settings
        $this->createDefaultSettings();

        // Install Filament
        if ($this->confirm('Do you want to install Filament admin panel?', true)) {
            $this->installFilament();
        }

        // Install demo data if requested
        if ($this->option('with-demo')) {
            $this->installDemoData();
        }

        // Clear cache
        $this->call('optimize:clear');

        $this->info('✅ Elmasry Starter Kit installed successfully!');
        $this->warn('➜ Create an admin user: php artisan make:filament-user');
        $this->warn('➜ Or login to: ' . config('starter-kit.filament.path', 'admin'));

        return Command::SUCCESS;
    }

    protected function createDefaultRolesAndPermissions(): void
    {
        $this->info('Creating default roles and permissions...');

        $permissions = [
            'view_dashboard',
            'view_users', 'create_users', 'edit_users', 'delete_users',
            'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
            'view_permissions', 'create_permissions', 'edit_permissions', 'delete_permissions',
            'view_settings', 'create_settings', 'edit_settings', 'delete_settings',
            'view_pages', 'create_pages', 'edit_pages', 'delete_pages', 'publish_pages',
            'view_translations', 'create_translations', 'edit_translations', 'delete_translations',
            'view_categories', 'create_categories', 'edit_categories', 'delete_categories',
            'view_tags', 'create_tags', 'edit_tags', 'delete_tags',
            'view_contacts', 'delete_contacts',
            'view_newsletters', 'delete_newsletters',
            'view_activity_log',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdmin = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions($permissions);

        $admin = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(array_filter($permissions, fn($p) => !str_contains($p, 'delete')));

        $editor = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $editor->syncPermissions([
            'view_dashboard',
            'view_pages', 'create_pages', 'edit_pages', 'publish_pages',
            'view_categories', 'create_categories', 'edit_categories',
            'view_tags', 'create_tags', 'edit_tags',
            'view_translations', 'edit_translations',
        ]);

        $this->info('Roles created: super_admin, admin, editor');
    }

    protected function createDefaultSettings(): void
    {
        $defaults = [
            ['key' => 'site_name', 'value' => config('app.name', 'My App'), 'group' => 'general', 'type' => 'text'],
            ['key' => 'site_description', 'value' => 'My Laravel Application', 'group' => 'general', 'type' => 'textarea'],
            ['key' => 'contact_email', 'value' => 'admin@example.com', 'group' => 'general', 'type' => 'text'],
            ['key' => 'meta_title', 'value' => 'My App', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'meta_description', 'value' => '', 'group' => 'seo', 'type' => 'textarea'],
            ['key' => 'facebook_url', 'value' => '', 'group' => 'social', 'type' => 'text'],
            ['key' => 'twitter_url', 'value' => '', 'group' => 'social', 'type' => 'text'],
            ['key' => 'instagram_url', 'value' => '', 'group' => 'social', 'type' => 'text'],
        ];

        foreach ($defaults as $setting) {
            \Elmasry\StarterKit\Models\Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->info('Default settings created.');
    }

    protected function installFilament(): void
    {
        $this->info('Installing Filament...');

        // Publish Filament config
        Artisan::call('filament:install', ['--panels' => true]);
        $this->line(Artisan::output());

        // Publish the admin panel provider stub
        $panelPath = app_path('Providers/Filament/AdminPanelProvider.php');
        if (!File::exists($panelPath) || $this->option('force')) {
            File::ensureDirectoryExists(app_path('Providers/Filament'));
            $stub = File::get(__DIR__ . '/../../stubs/filament-admin-panel-provider.stub');
            File::put($panelPath, $stub);
            $this->info('AdminPanelProvider created.');
        }

        // Create Filament theme
        Artisan::call('filament:install', ['--type' => 'theme']);
        $this->line(Artisan::output());
    }

    protected function installDemoData(): void
    {
        $this->info('Installing demo data...');

        // Create demo pages
        $demoPages = [
            [
                'title' => ['en' => 'About Us', 'ar' => 'من نحن'],
                'slug' => ['en' => 'about-us', 'ar' => 'من-نحن'],
                'content' => ['en' => '<p>This is the about us page.</p>', 'ar' => '<p>هذه صفحة من نحن</p>'],
                'status' => 'published',
            ],
            [
                'title' => ['en' => 'Privacy Policy', 'ar' => 'سياسة الخصوصية'],
                'slug' => ['en' => 'privacy-policy', 'ar' => 'سياسة-الخصوصية'],
                'content' => ['en' => '<p>Your privacy policy content here.</p>', 'ar' => '<p>محتوى سياسة الخصوصية هنا</p>'],
                'status' => 'published',
            ],
            [
                'title' => ['en' => 'Terms of Service', 'ar' => 'شروط الخدمة'],
                'slug' => ['en' => 'terms-of-service', 'ar' => 'شروط-الخدمة'],
                'content' => ['en' => '<p>Your terms of service content here.</p>', 'ar' => '<p>محتوى شروط الخدمة هنا</p>'],
                'status' => 'published',
            ],
        ];

        foreach ($demoPages as $pageData) {
            \Elmasry\StarterKit\Models\Page::firstOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }

        $this->info('Demo pages created.');
    }
}
