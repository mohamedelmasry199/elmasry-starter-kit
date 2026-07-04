<?php

namespace Elmasry\StarterKit\Filament\Resources;

use Elmasry\StarterKit\Filament\Resources\UserResource\Pages;
use Elmasry\StarterKit\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->visibleOn('create')
                    ->requiredOn('create')
                    ->rule(Password::default()),
                Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->revealable()
                    ->visibleOn('create')
                    ->requiredOn('create')
                    ->same('password'),
                Forms\Components\Select::make('locale')
                    ->options([
                        'en' => 'English',
                        'ar' => 'Arabic',
                    ])
                    ->default('en'),
                Forms\Components\Select::make('timezone')
                    ->options([
                        'UTC' => 'UTC',
                        'Africa/Cairo' => 'Africa/Cairo',
                        'America/New_York' => 'America/New_York',
                        'America/Chicago' => 'America/Chicago',
                        'America/Denver' => 'America/Denver',
                        'America/Los_Angeles' => 'America/Los_Angeles',
                        'Asia/Dubai' => 'Asia/Dubai',
                        'Asia/Riyadh' => 'Asia/Riyadh',
                        'Asia/Tokyo' => 'Asia/Tokyo',
                        'Asia/Shanghai' => 'Asia/Shanghai',
                        'Asia/Kolkata' => 'Asia/Kolkata',
                        'Australia/Sydney' => 'Australia/Sydney',
                        'Europe/London' => 'Europe/London',
                        'Europe/Paris' => 'Europe/Paris',
                        'Europe/Berlin' => 'Europe/Berlin',
                        'Europe/Moscow' => 'Europe/Moscow',
                    ])
                    ->default('UTC'),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('locale')
                    ->badge()
                    ->formatStateUsing(fn ($state) => strtoupper($state)),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                    ]),
                Tables\Filters\SelectFilter::make('locale')
                    ->options([
                        'en' => 'English',
                        'ar' => 'Arabic',
                    ]),
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
