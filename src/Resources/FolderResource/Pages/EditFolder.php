<?php

namespace Tobil476\FilamentMediaManager\Resources\FolderResource\Pages;

use Tobil476\FilamentMediaManager\Resources\FolderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFolder extends EditRecord
{
    protected static string $resource = FolderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
