<?php

namespace Elmasry\StarterKit\Filament\Resources;

use Elmasry\StarterKit\Filament\Resources\PageResource\Pages;
use Elmasry\StarterKit\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Page Details')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Content')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                Forms\Components\RichEditor::make('content')
                                    ->columnSpanFull()
                                    ->maxLength(65535),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                        'archived' => 'Archived',
                                    ])
                                    ->default('draft')
                                    ->required(),
                                Forms\Components\Select::make('template')
                                    ->options([
                                        'default' => 'Default',
                                        'full_width' => 'Full Width',
                                        'sidebar' => 'With Sidebar',
                                        'landing' => 'Landing Page',
                                    ])
                                    ->default('default'),
                                Forms\Components\FileUpload::make('featured_image')
                                    ->image()
                                    ->directory('pages/featured-images')
                                    ->maxSize(2048),
                            ])
                            ->columns(2),
                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('meta_description')
                                    ->maxLength(65535)
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('meta_keywords')
                                    ->maxLength(255)
                                    ->helperText('Comma-separated keywords'),
                            ]),
                        Forms\Components\Tabs\Tab::make('Publishing')
                            ->schema([
                                Forms\Components\Select::make('author_id')
                                    ->label('Author')
                                    ->relationship('author', 'name')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0),
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Publish Date'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'warning',
                        'published' => 'success',
                        'archived' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Author')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),
                Tables\Filters\SelectFilter::make('template')
                    ->options([
                        'default' => 'Default',
                        'full_width' => 'Full Width',
                        'sidebar' => 'With Sidebar',
                        'landing' => 'Landing Page',
                    ]),
            ])
            ->recordActions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('toggle_status')
                    ->label(fn ($record) => $record->status === 'published' ? 'Unpublish' : 'Publish')
                    ->icon(fn ($record) => $record->status === 'published' ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->color(fn ($record) => $record->status === 'published' ? 'warning' : 'success')
                    ->action(function ($record) {
                        $record->update([
                            'status' => $record->status === 'published' ? 'draft' : 'published',
                        ]);
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
