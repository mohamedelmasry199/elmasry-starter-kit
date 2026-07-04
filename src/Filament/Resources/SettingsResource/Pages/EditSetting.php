<?php

namespace Elmasry\StarterKit\Filament\Resources\SettingsResource\Pages;

use Elmasry\StarterKit\Filament\Resources\SettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
