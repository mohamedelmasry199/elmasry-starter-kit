<?php

namespace Elmasry\StarterKit\Filament\Resources\PageResource\Pages;

use Elmasry\StarterKit\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
