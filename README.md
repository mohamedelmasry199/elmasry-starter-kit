# Elmasry Starter Kit v5 - Full Code Walkthrough

**Filament v5 | Laravel 11/12 | PHP 8.2+ | Livewire v4 | Tailwind v4**

This README explains **every file and every line of code** I wrote so you can rebuild this entire package from scratch with full understanding. I explain **why** I wrote each line, not just **what** it does.

---

## The Thought Process Before Writing Any Code

I answered these questions first:

**Q: What will this package do for the user?**
A: When someone creates a new Laravel project and installs this package, they get:
- Filament admin panel with dashboard
- Arabic + English languages auto-detected
- Roles & permissions system
- CMS pages, settings, contact forms, categories, tags
- All migrations, models, and UI ready to use

**Q: How does Laravel discover packages?**
A: Laravel reads composer.json → finds extra.laravel.providers → registers our ServiceProvider → ServiceProvider's boot() loads migrations, routes, views, translations.

**Q: What files does every Laravel package need?**
1. composer.json - Identifies the package to Composer and Laravel
2. ServiceProvider - Entry point that registers everything
3. config/*.php - Default config values with env() overrides
4. database/migrations/*.php - Database table definitions
5. src/Models/*.php - Eloquent models
6. routes/*.php - Web routes
7. resources/lang/*.php - Translation files for multi-language
8. src/Console/*.php - Artisan commands for installation
9. src/Filament/Resources/*.php - Admin panel UI

**Q: How does the user install it?**
A: composer require elmasry/starter-kit → php artisan starter-kit:install → command publishes files, runs migrations, seeds roles/permissions/settings

---

## File-by-File Explanation

### 1. composer.json

I start every package with composer.json. This file tells Composer who we are, what we need, and how to autoload our classes.

Key parts:
- **name**: "elmasry/starter-kit" - This is the Composer identifier. Users run "composer require elmasry/starter-kit".
- **PHP 8.2+ required** (was 8.1 in v3)
- **Laravel 11.28+ or 12.x** (was 10|11)
- **Filament ^5.0 + Livewire ^4.0** (was Filament ^3.0 with Livewire v3) Users run "composer require elmasry/starter-kit".
- **autoload.psr-4**: Maps namespace "Elmasry\StarterKit" to "src/" folder. When PHP sees "use Elmasry\StarterKit\Models\User", it loads "src/Models/User.php".
- **extra.laravel.providers**: Laravel auto-discovery. When installed, Laravel automatically registers our ServiceProvider without the user adding to config/app.php.
- **require**: All dependencies. The caret ^ means "any compatible version": ^8.1 = PHP 8.1+, ^3.0 = Filament 3.x.

Why these packages?
- filament/filament - Admin panel UI (tables, forms, navigation)
- spatie/laravel-permission - Roles/permissions database + HasRoles trait
- spatie/laravel-translatable - Stores translations as JSON in single columns
- spatie/laravel-medialibrary - File uploads attached to any model
- spatie/laravel-activitylog - Logs who did what to which model

### 2. src/StarterKitServiceProvider.php - Entry Point

Every Laravel package needs a ServiceProvider. This is the bridge between our package and Laravel.

The register() method runs first, before all providers are registered. I only put config merging here because it needs to be available early:
- mergeConfigFrom() makes config('starter-kit.locale.default') work. If user publishes config to their project, their values override ours.

The boot() method runs after all providers are registered. This is where I load files:
- loadMigrationsFrom() - Without this, php artisan migrate wouldn't see our migrations
- loadTranslationsFrom() - Makes __('starter-kit::messages.dashboard') work. The "starter-kit::" namespace prevents conflicts
- loadViewsFrom() - Makes view('starter-kit::contact') work
- loadRoutesFrom() - Registers our routes like /language/{locale}
- publishes() - First arg is [package_path => project_path]. Tag is for --tag= flag. Users run "php artisan vendor:publish --tag=starter-kit-config"
- runningInConsole() check - Only register commands in CLI, not web requests (saves memory)

### 3. config/starter-kit.php

I use env() everywhere so users can override values in their .env file without editing the config. Example: env('APP_LOCALE', 'en') means "check .env first, default to 'en'". Setting FILAMENT_PATH=dashboard in .env changes admin URL to /dashboard.

The locales array ['en', 'ar'] defines available languages. To add French, just add 'fr' here.

### 4. Database Migrations

Each migration is an anonymous class (new class extends Migration) with up() and down() methods. The timestamp prefix determines execution order.

**create_settings_table**: Key-value design. Extensible without migrations - new setting = new row, not new column. key is unique. group organizes in UI. type tells Filament what form field to render (text/boolean/rich_editor).

**create_pages_table**: JSON columns for translations. spatie/laravel-translatable stores {"en":"About","ar":"من نحن"} in a single column. When locale is 'ar', ->title returns Arabic automatically. Without JSON, you'd need title_en, title_ar columns.

author_id has foreign key constraint with nullOnDelete - if user is deleted, their pages stay but author_id becomes null. Prevents deletion failure.

**create_translations_table**: Separate from model translations. Model JSON = dynamic content (page titles). This table = static UI text (button labels, system messages). Unique composite index prevents duplicate translations.

**create_categories_table**: Self-referencing parent_id creates hierarchy: parent_id = null = top level, parent_id = 5 = child of category 5. Enables Electronics → Phones → iPhones.

**create_tags_table + taggables_table**: Polymorphic pivot. morphs('taggable') creates taggable_type (string) + taggable_id (bigint). "taggable_type=App\Models\Page, taggable_id=15, tag_id=3" means "tag #3 is on page #15". ANY model can be tagged.

**add_language_fields_to_users_table**: Schema::table() modifies existing users table. locale (preferred language), timezone (correct date display), is_active (disable without delete), last_login tracking (security).

### 5. Models

**User.php**: Extends Authenticatable so Laravel auth works (login, password hashing, sessions). Uses HasRoles trait from Spatie (adds assignRole, hasPermissionTo methods).  protects against mass-assignment.  converts is_active to boolean automatically.  excludes password from JSON. hasMany(Page::class, 'author_id') - second param specifies the foreign key column.

**Setting.php**: Simple model with scopeByGroup. Scopes make queries readable: Setting::byGroup('general')->get() vs Setting::where('group', 'general')->get().

**Page.php**: Uses HasTranslations trait.  lists which JSON columns contain translations. When you call ->title, it internally does json_decode(->attributes['title'], true)[app()->getLocale()]. Scopes: Page::published()->get() is cleaner than ->where('status', 'published').

**Category.php**: Self-referential relationships. parent() = belongsTo same model. children() = hasMany same model. ->parent gets parent, ->children gets subcategories.

**Tag.php**: morphedByMany(Page::class, 'taggable') is the inverse of the polymorphic pivot. ->pages returns all pages with this tag.

### 6. Filament Resources

Filament v3 pattern: Resource class defines model/navigation/form/table. Each Resource maps routes to pages (ListRecords, CreateRecord, EditRecord).

**UserResource**:  = User::class binds the model.  = 'heroicon-o-users' sets sidebar icon.  groups related resources.  orders within group.

Form: TextInput::make('name')->required() creates input field. email()->unique(ignoreRecord: true) - ignoreRecord means when editing, don't flag current user's email as duplicate. password->visibleOn('create') - only show on create, prevents accidentally blanking password on edit.

Table: TextColumn::make('roles.name')->badge() uses dot notation to access related model's column. badge() renders as pill. IconColumn::make('is_active')->boolean() shows checkmark/X. Filters use relationship() to join related table.

**PageResource**: Tabs organize complex forms. Tab::make('Content') contains title/slug/content/status. Tab::make('SEO') for meta fields. Tab::make('Publishing') for author/date/sort. Since Page uses HasTranslations, Filament auto-detects translatable fields and shows language switcher.

**SettingsResource**: Uses live() pattern. Select::make('type')->live() means the form re-renders when type changes. Then value field adapts: Toggle::make('value')->visible(fn (Get ) => ('type') === 'boolean'), RichEditor::make('value')->visible(fn (Get ) => ('type') === 'rich_editor').

### 7. Middleware - LocalizationMiddleware

Priority: 1. Session (user chose language) → 2. Browser header (auto-detect) → 3. Config default.

Session::has('locale') checks if user previously switched language (stored in session). HTTP_ACCEPT_LANGUAGE header contains browser language. App::setLocale() tells Laravel which language to use for __() translations.

### 8. Routes

Route::get('/language/{locale}', function () {...}) - The language switcher. Stores choice in session so middleware picks it up. Validates against available locales. redirect()->back() keeps user on same page.

### 9. Install Command - InstallStarterKitCommand.php

This is what runs when user types "php artisan starter-kit:install". The handle() method:

1. Calls vendor:publish with --tag to copy config and migrations to project
2. Calls migrate to run all published migrations
3. Publishes Spatie Permission's migration and runs it
4. Creates 33 permissions using Permission::firstOrCreate() to prevent duplicates
5. Creates 3 roles (super_admin, admin, editor) and syncs appropriate permissions
6. Creates 8 default settings (site_name, meta_title, social URLs, etc.)
7. Optionally installs Filament admin panel
8. With --with-demo flag, creates sample pages in both Arabic and English
9. Clears cache

firstOrCreate() is critical - prevents errors if running command twice.

### 10. Translation Files

resources/lang/en/messages.php and resources/lang/ar/messages.php. Same keys (~100), different values. Usage: __('starter-kit::messages.dashboard') returns "Dashboard" in English or "لوحة التحكم" in Arabic.

### 11. Complete Directory Structure

`
elmasry-starter-kit/
├── composer.json
├── config/starter-kit.php
├── database/migrations/ (11 files)
├── resources/lang/{en,ar}/messages.php
├── resources/views/contact.blade.php
├── routes/web.php
├── src/
│   ├── Console/InstallStarterKitCommand.php
│   ├── Commands/CreateFilamentUserCommand.php
│   ├── Filament/Resources/ (7 resources, 21 page files + 3 widgets)
│   ├── Http/Middleware/LocalizationMiddleware.php
│   ├── Http/Controllers/ContactController.php + NewsletterController.php
│   ├── Models/ (User, Setting, Page, Translation, Category, Tag, Contact, Newsletter, Media)
│   └── StarterKitServiceProvider.php
└── stubs/filament-admin-panel-provider.stub
`

66 files total, 3174 lines of code.

---

## How to Rebuild from Scratch

### Step 1: Create directory structure
mkdir elmasry-starter-kit && cd elmasry-starter-kit
mkdir -p src/{Console,Commands,Models} src/Filament/Resources/{DashboardResource,UserResource,RoleResource,PermissionResource,SettingsResource,PageResource,TranslationResource} src/Filament/{Pages,Widgets} src/Http/{Middleware,Controllers} database/migrations config resources/lang/{en,ar} resources/views routes stubs tests

### Step 2: composer.json
Define name, autoload PSR-4 mapping, extra.laravel.providers for auto-discovery, and require all dependencies.

### Step 3: ServiceProvider
Extend ServiceProvider. register() merges config. boot() loads migrations/views/routes/translations, registers publishes() tags, and registers commands.

### Step 4: config/starter-kit.php
Return array with locale, filament, user, seo, contact, media settings. Use env() for overridable values.

### Step 5: Migrations
Create timestamped files for each table. Use Schema::create() for new tables, Schema::table() for modifying existing. Key patterns: JSON for translations, foreignId for relationships, morphs for polymorphic, unique() for composite indexes.

### Step 6: Models
Each model extends Model (or Authenticatable for User). Add fillable, casts, hidden, relationships, scopes. User uses HasRoles trait. Page/Category/Tag use HasTranslations trait.

### Step 7: Filament Resources
Each resource needs Resource class + 3 page classes (List, Create, Edit). Resource defines model, navigation, form(), table(). Form uses TextInput/Select/Toggle/etc. Table uses TextColumn/IconColumn with searchable/sortable/badge. Filters use relationship().

### Step 8: InstallCommand
The handle() method: publish config → publish migrations → run migrate → create permissions with firstOrCreate → create roles → sync permissions → create default settings → optionally install Filament.

### Step 9: Translation files
resources/lang/en/messages.php and ar/messages.php with identical key structures.

### Step 10: Git
git init → git add . → git commit -m "Initial commit" → create GitHub repo → git remote add origin URL → git push -u origin main
