# Elmasry Starter Kit

A comprehensive Laravel starter package that provides everything you need to kickstart any Laravel project:

- **Filament v3** Admin Panel
- **Multi-Language** (Arabic + English) with automatic detection
- **Roles & Permissions** (Spatie Permission + Filament Shield-ready)
- **CMS Pages** with translatable content
- **Settings Management**
- **Contact Forms & Newsletters**
- **Categories & Tags**
- **Activity Log**
- **Media Library**
- **SEO tools**

## Requirements

- PHP 8.1+
- Laravel 10.x | 11.x
- Filament 3.x
- MySQL/MariaDB

## Installation

### 1. Create a new Laravel project

```bash
composer create-project laravel/laravel my-project
cd my-project
```

### 2. Install the package

```bash
composer require elmasry/starter-kit
```

### 3. Run the installer

```bash
php artisan starter-kit:install
```

This will:
- Publish configuration, migrations, and translations
- Run all database migrations
- Install Spatie Permissions
- Create default roles: `super_admin`, `admin`, `editor`
- Create default permissions for all resources
- Create default settings (site name, SEO, social links)
- Install Filament admin panel (optional prompt)
- Ask if you want to install Filament (recommended: YES)

### 4. Create an admin user

```bash
php artisan starter-kit:make-user
```

Or use Filament's built-in command:

```bash
php artisan make:filament-user
```

### 5. Access your dashboard

Navigate to `/admin` (or your configured path).

## Optional: Install with demo data

```bash
php artisan starter-kit:install --with-demo
```

This creates sample pages (About Us, Privacy Policy, Terms of Service) in both Arabic and English.

---

## Package Architecture

### Directory Structure

```
elmasry-starter-kit/
├── config/
│   └── starter-kit.php          # Package configuration
├── database/
│   └── migrations/              # All database migrations
├── resources/
│   ├── lang/
│   │   ├── en/messages.php      # English translations
│   │   └── ar/messages.php      # Arabic translations
│   └── views/                   # Blade views
├── routes/
│   └── web.php                  # Package routes
├── src/
│   ├── Console/
│   │   └── InstallStarterKitCommand.php
│   ├── Commands/
│   │   └── CreateFilamentUserCommand.php
│   ├── Filament/
│   │   ├── Resources/
│   │   │   ├── DashboardResource/
│   │   │   ├── UserResource/
│   │   │   ├── RoleResource/
│   │   │   ├── PermissionResource/
│   │   │   ├── SettingsResource/
│   │   │   ├── PageResource/
│   │   │   └── TranslationResource/
│   │   └── Widgets/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ContactController.php
│   │   │   └── NewsletterController.php
│   │   └── Middleware/
│   │       └── LocalizationMiddleware.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Setting.php
│   │   ├── Page.php
│   │   ├── Translation.php
│   │   ├── Category.php
│   │   ├── Tag.php
│   │   ├── Contact.php
│   │   ├── Newsletter.php
│   │   └── Media.php
│   └── StarterKitServiceProvider.php
├── stubs/
│   └── filament-admin-panel-provider.stub
├── composer.json
└── README.md
```

---

## Features in Detail

### 1. Multi-Language System

- **Auto-detection** of browser language
- **Session-based** language switching
- Arabic (RTL) and English (LTR) support
- Language switcher route: `/language/{locale}`
- Translatable models using `spatie/laravel-translatable`
- Database translations table for custom translation keys

### 2. Roles & Permissions

**Default Roles:**
| Role | Description |
|------|-------------|
| `super_admin` | Full access to everything |
| `admin` | CRUD all resources (except delete) |
| `editor` | Content management only |

**Permission Groups:**
- Dashboard, Users, Roles, Permissions
- Settings (all CRUD)
- Pages (all CRUD + publish)
- Translations (all CRUD)
- Categories, Tags (all CRUD)
- Contacts, Newsletters (view + delete)
- Activity Log (view only)

### 3. Filament Admin Panel Resources

| Resource | Description |
|----------|-------------|
| **Dashboard** | Stats overview, latest users, recent activity |
| **Users** | Manage users with roles, locale, timezone |
| **Roles** | Create/edit roles with permission checkboxes |
| **Permissions** | Fine-grained permission management |
| **Settings** | Key-value settings with types (text, rich editor, boolean, file) |
| **Pages** | CMS pages with translatable content, SEO, publishing |
| **Translations** | Manage translation keys per locale |

### 4. Database Tables

| Table | Purpose |
|-------|---------|
| `users` | Extended with locale, timezone, is_active, login tracking |
| `settings` | Key-value settings store |
| `pages` | CMS pages with JSON translations |
| `translations` | Custom translation keys |
| `categories` | Hierarchical categories (parent_id) |
| `tags` | Flat tags with polymorphic pivot |
| `contacts` | Contact form submissions |
| `newsletters` | Email newsletter subscriptions |
| `media` | Spatie Media Library |
| `activity_log` | Spatie Activity Log |
| `model_has_roles` | Spatie Permissions |
| `model_has_permissions` | Spatie Permissions |
| `role_has_permissions` | Spatie Permissions |
| `taggables` | Polymorphic tag pivot |

### 5. Artisan Commands

```bash
# Full installation
php artisan starter-kit:install
php artisan starter-kit:install --force
php artisan starter-kit:install --with-demo

# Create admin user
php artisan starter-kit:make-user
```

### 6. Configuration

Publish the config:

```bash
php artisan vendor:publish --tag=starter-kit-config
```

Key config options in `config/starter-kit.php`:

```php
'locale' => [
    'default' => 'en',
    'locales' => ['en', 'ar'],
],

'filament' => [
    'path' => 'admin',       // Admin panel URL path
    'domain' => null,        // Custom domain
],
```

---

## How to Build This Package Yourself

If you want to recreate this package from scratch, follow these steps:

### Step 1: Create the package directory

```bash
mkdir elmasry-starter-kit
cd elmasry-starter-kit
mkdir -p src/{Console,Commands,Models}
mkdir -p src/Filament/{Resources/{DashboardResource,UserResource,RoleResource,PermissionResource,SettingsResource,PageResource,TranslationResource},Pages,Widgets}
mkdir -p src/Http/{Middleware,Controllers}
mkdir -p database/migrations
mkdir -p config
mkdir -p resources/lang/{en,ar}
mkdir -p resources/views
mkdir -p routes
mkdir -p stubs
mkdir -p tests
```

### Step 2: composer.json

Create `composer.json` with:
- Name: `elmasry/starter-kit`
- PSR-4 autoload for `Elmasry\StarterKit\` => `src/`
- Laravel auto-discovery in `extra.laravel.providers`
- Require: `laravel/framework`, `filament/filament`, `spatie/laravel-permission`, `spatie/laravel-translatable`, `spatie/laravel-medialibrary`, `spatie/laravel-activitylog`, `mcamara/laravel-localization`

```json
{
    "name": "elmasry/starter-kit",
    "autoload": {
        "psr-4": {
            "Elmasry\\StarterKit\\": "src/"
        }
    },
    "extra": {
        "laravel": {
            "providers": [
                "Elmasry\\StarterKit\\StarterKitServiceProvider"
            ]
        }
    },
    "require": {
        "php": "^8.1",
        "laravel/framework": "^10.0|^11.0",
        "filament/filament": "^3.0",
        "spatie/laravel-permission": "^6.0",
        "spatie/laravel-translatable": "^6.0",
        "spatie/laravel-medialibrary": "^10.0|^11.0",
        "spatie/laravel-activitylog": "^4.0",
        "mcamara/laravel-localization": "^1.6|^2.0"
    }
}
```

### Step 3: ServiceProvider

Create `src/StarterKitServiceProvider.php`:

```php
namespace Elmasry\StarterKit;

use Illuminate\Support\ServiceProvider;

class StarterKitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/starter-kit.php', 'starter-kit');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'starter-kit');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'starter-kit');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->publishes([
            __DIR__.'/../config/starter-kit.php' => config_path('starter-kit.php'),
        ], 'starter-kit-config');

        $this->publishes([
            __DIR__.'/../resources/lang' => lang_path('vendor/starter-kit'),
        ], 'starter-kit-lang');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'starter-kit-migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallStarterKitCommand::class,
            ]);
        }
    }
}
```

### Step 4: Create the migrations

Create timestamped migration files in `database/migrations/` for:
1. `create_settings_table` - key/value settings
2. `create_pages_table` - translatable CMS pages
3. `create_translations_table` - custom translation keys
4. `create_media_table` - Spatie media library
5. `create_activity_log_table` - Spatie activity log
6. `create_categories_table` - hierarchical categories
7. `create_tags_table` + `create_taggables_table` - tags with polymorphic pivot
8. `create_contacts_table` - contact form submissions
9. `create_newsletters_table` - email subscriptions
10. `add_language_fields_to_users_table` - extend users table

### Step 5: Create Models

Create models in `src/Models/`:
- `User.php` - extends `Authenticatable`, uses `HasRoles` trait, add locale/timezone/is_active fields
- `Setting.php` - basic model with `scopeByGroup`
- `Page.php` - uses `Spatie\Translatable\HasTranslations`, scopes for published/draft
- `Translation.php` - group/key/value/locale
- `Category.php` - translatable, self-referencing parent/children
- `Tag.php` - translatable, morphToMany
- `Contact.php`, `Newsletter.php` - simple models

### Step 6: Create Filament Resources

For each resource in `src/Filament/Resources/`:
- Create Resource class with form(), table(), getPages()
- Create Pages/ListRecords, Pages/CreateRecord, Pages/EditRecord
- Dashboard: StatsOverview widget, LatestUsers widget, RecentActivity widget

### Step 7: Create Install Command

In `src/Console/InstallStarterKitCommand.php`:
- `php artisan starter-kit:install`
- Publishes config, migrations, translations
- Creates default roles (`super_admin`, `admin`, `editor`)
- Creates all permissions
- Creates default settings
- Optionally installs Filament
- Optionally creates demo data with `--with-demo`

### Step 8: Create translations

In `resources/lang/en/messages.php` and `resources/lang/ar/messages.php`:
- All UI strings in both languages
- Same key structure for both files

### Step 9: Configuration

In `config/starter-kit.php`:
- Locale settings (default, available locales, fallback)
- Filament panel settings (path, domain)
- User defaults (locale, timezone)
- SEO defaults
- Media settings

### Step 10: Git setup

```bash
git init
git add .
git commit -m "Initial commit: Laravel Starter Kit with Filament, multi-language, roles & permissions"
```

### Step 11: GitHub

```bash
# Create repo on GitHub first, then:
git remote add origin https://github.com/YOUR_USERNAME/elmasry-starter-kit.git
git branch -M main
git push -u origin main
```

---

## Publishing to GitHub

```bash
git init
git add .
git commit -m "Initial commit: Elmasry Starter Kit"
git branch -M main
git remote add origin https://github.com/elmasry/elmasry-starter-kit.git
git push -u origin main
```

## License

MIT
