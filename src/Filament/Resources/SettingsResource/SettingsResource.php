<?php

namespace Elmasry\StarterKit\Filament\Resources;

use Elmasry\StarterKit\Filament\Resources\SettingsResource\Pages;
use Elmasry\StarterKit\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Artisan;

class SettingsResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('group')
                    ->options([
                        'general' => 'General',
                        'seo' => 'SEO',
                        'social' => 'Social',
                        'mail' => 'Mail',
                        'appearance' => 'Appearance',
                        'security' => 'Security',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('value')
                    ->visible(fn ($get) => in_array($get('type'), ['text', 'boolean']))
                    ->maxLength(65535),
                Forms\Components\Textarea::make('value')
                    ->visible(fn ($get) => $get('type') === 'textarea')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('value')
                    ->visible(fn ($get) => $get('type') === 'rich_editor')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('value')
                    ->visible(fn ($get) => in_array($get('type'), ['image', 'file']))
                    ->columnSpanFull(),
                Forms\Components\Select::make('type')
                    ->options([
                        'text' => 'Text',
                        'textarea' => 'Textarea',
                        'rich_editor' => 'Rich Editor',
                        'image' => 'Image',
                        'file' => 'File',
                        'boolean' => 'Boolean',
                    ])
                    ->default('text')
                    ->required()
                    ->live(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('group')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'general' => 'gray',
                        'seo' => 'warning',
                        'social' => 'info',
                        'mail' => 'danger',
                        'appearance' => 'success',
                        'security' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'text' => 'gray',
                        'textarea' => 'gray',
                        'rich_editor' => 'success',
                        'image' => 'info',
                        'file' => 'warning',
                        'boolean' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'general' => 'General',
                        'seo' => 'SEO',
                        'social' => 'Social',
                        'mail' => 'Mail',
                        'appearance' => 'Appearance',
                        'security' => 'Security',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'text' => 'Text',
                        'textarea' => 'Textarea',
                        'rich_editor' => 'Rich Editor',
                        'image' => 'Image',
                        'file' => 'File',
                        'boolean' => 'Boolean',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('clear_cache')
                    ->label('Clear Cache')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->action(function () {
                        Artisan::call('cache:clear');
                        Notification::make()
                            ->title('Cache cleared successfully')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
