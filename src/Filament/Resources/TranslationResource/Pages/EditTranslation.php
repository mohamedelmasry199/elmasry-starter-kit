<?php

namespace Elmasry\StarterKit\Filament\Resources\TranslationResource\Pages;

use Elmasry\StarterKit\Filament\Resources\TranslationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTranslation extends EditRecord
{
    protected static string $resource = TranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
