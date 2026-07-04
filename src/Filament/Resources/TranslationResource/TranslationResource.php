<?php

namespace Elmasry\StarterKit\Filament\Resources;

use Elmasry\StarterKit\Filament\Resources\TranslationResource\Pages;
use Elmasry\StarterKit\Models\Translation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class TranslationResource extends Resource
{
    protected static ?string $model = Translation::class;

    protected static ?string $navigationIcon = 'heroicon-o-language';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('group')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('value')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Select::make('locale')
                    ->options([
                        'en' => 'English',
                        'ar' => 'Arabic',
                    ])
                    ->default('en')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('group')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('locale')
                    ->badge()
                    ->formatStateUsing(fn ($state) => strtoupper($state))
                    ->color(fn (string $state): string => $state === 'en' ? 'success' : 'primary'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('locale')
                    ->options([
                        'en' => 'English',
                        'ar' => 'Arabic',
                    ]),
                Tables\Filters\SelectFilter::make('group')
                    ->options(fn () => Translation::pluck('group')->unique()->filter()->values()->mapWithKeys(fn ($group) => [$group => $group])->toArray()),
            ])
            ->recordActions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('import')
                    ->label('Import')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('warning')
                    ->form([
                        Forms\Components\FileUpload::make('file')
                            ->label('JSON File')
                            ->acceptedFileTypes(['application/json'])
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $path = Storage::disk('public')->path($data['file']);
                        $translations = json_decode(file_get_contents($path), true);
                        $count = 0;
                        foreach ($translations as $group => $items) {
                            foreach ($items as $key => $value) {
                                if (is_array($value)) {
                                    foreach ($value as $locale => $translated) {
                                        Translation::updateOrCreate(
                                            ['group' => $group, 'key' => $key, 'locale' => $locale],
                                            ['value' => $translated]
                                        );
                                        $count++;
                                    }
                                } else {
                                    Translation::updateOrCreate(
                                        ['group' => $group, 'key' => $key, 'locale' => 'en'],
                                        ['value' => $value]
                                    );
                                    $count++;
                                }
                            }
                        }
                        Storage::disk('public')->delete($data['file']);
                        Notification::make()
                            ->title("Imported {$count} translations")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('export')
                    ->label('Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        $translations = Translation::all()->groupBy('group')->map(function ($items) {
                            return $items->groupBy('key')->map(function ($items) {
                                return $items->pluck('value', 'locale')->toArray();
                            })->toArray();
                        })->toArray();
                        $json = json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        $filename = 'translations-' . now()->format('Y-m-d-His') . '.json';
                        Storage::disk('public')->put($filename, $json);
                        Notification::make()
                            ->title("Translations exported as {$filename}")
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
            'index' => Pages\ListTranslations::route('/'),
            'create' => Pages\CreateTranslation::route('/create'),
            'edit' => Pages\EditTranslation::route('/{record}/edit'),
        ];
    }
}
