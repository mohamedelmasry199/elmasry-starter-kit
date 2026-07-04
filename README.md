# Elmasry Starter Kit

**Laravel Starter Kit with Filament v5, Multi-Language (AR/EN), Roles & Permissions, CMS Pages, Settings, and more.**

A comprehensive Laravel package that provides everything you need to kickstart any Laravel project — admin panel, authentication, localization, and essential CRUD features — all with one command.

## Requirements

- PHP 8.2+
- Laravel 11.28+ / 12.x / 13.x
- MySQL / MariaDB / PostgreSQL

## Installation

```bash
# 1. Create a new Laravel project
composer create-project laravel/laravel my-project
cd my-project

# 2. Install the package
composer require elmasry/starter-kit

# 3. Run the installer
php artisan starter-kit:install
```

The installer will:
- Publish configuration and migrations
- Run all database migrations
- Install Spatie Roles & Permissions
- Create 3 roles (super_admin, admin, editor) with 33 permissions
- Create default settings
- Optionally install Filament admin panel

### With demo data

```bash
php artisan starter-kit:install --with-demo
```

### Create an admin user

```bash
php artisan starter-kit:make-user
# Or use Filament's built-in command:
php artisan make:filament-user
```

Access your dashboard at `/admin` (or your configured path).

## Features

### Admin Panel (Filament v5)
- Dashboard with stats, latest users, recent activity
- Users management with roles, locale, timezone
- Roles & Permissions (CRUD with grouped permission checkboxes)
- CMS Pages with translatable content, SEO, publishing tabs
- Settings manager (key-value with dynamic field types)
- Translations manager with import/export

### Multi-Language (Arabic + English)
- Browser language auto-detection
- Session-based language switching
- Translatable models (Pages, Categories, Tags)
- `GET /language/{locale}` language switcher route

### Database Tables (11 migrations)
| Table | Purpose |
|-------|---------|
| `settings` | Key-value settings |
| `pages` | CMS pages with JSON translations |
| `translations` | Custom translation keys |
| `categories` | Hierarchical categories |
| `tags` + `taggables` | Polymorphic tags |
| `contacts` | Contact form submissions |
| `newsletters` | Email subscriptions |
| `media` | Spatie Media Library |
| `activity_log` | Spatie Activity Log |

### Artisan Commands

```bash
php artisan starter-kit:install           # Full installation
php artisan starter-kit:install --force   # Overwrite existing files
php artisan starter-kit:install --with-demo # Include demo pages
php artisan starter-kit:make-user         # Create admin user
```

## Configuration

Publish config:

```bash
php artisan vendor:publish --tag=starter-kit-config
```

Key options in `config/starter-kit.php`:

```php
'locale' => [
    'default' => env('APP_LOCALE', 'en'),
    'locales' => ['en', 'ar'],
],
'filament' => [
    'path' => env('FILAMENT_PATH', 'admin'),  // Admin panel URL
],
```

Set `FILAMENT_PATH=dashboard` in `.env` to change the admin URL.

## Included Packages

| Package | Version |
|---------|---------|
| filament/filament | ^5.0 |
| livewire/livewire | ^4.0 |
| spatie/laravel-permission | ^8.0 |
| spatie/laravel-translatable | ^6.0 |
| spatie/laravel-medialibrary | ^11.0 |
| spatie/laravel-activitylog | ^4.12 |
| mcamara/laravel-localization | ^2.0 |
| spatie/laravel-sluggable | ^3.0 |
| spatie/laravel-sitemap | ^7.0 |

## Full Walkthrough

For a detailed code walkthrough explaining every file and why each line was written, see [WALKTHROUGH.md](WALKTHROUGH.md).

## License

MIT
