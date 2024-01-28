<?php

namespace Tschucki\FilamentWorkflows\Resources\FilamentWorkflowResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Tschucki\FilamentWorkflows\Resources\FilamentWorkflowResource;

class EditFilamentWorkflow extends EditRecord
{
    protected static string $resource = FilamentWorkflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
