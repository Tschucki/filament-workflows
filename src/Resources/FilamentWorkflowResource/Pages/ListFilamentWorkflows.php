<?php

namespace Tschucki\FilamentWorkflows\Resources\FilamentWorkflowResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Tschucki\FilamentWorkflows\Resources\FilamentWorkflowResource;

class ListFilamentWorkflows extends ListRecords
{
    protected static string $resource = FilamentWorkflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
