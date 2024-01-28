<?php

namespace Tschucki\FilamentWorkflows\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Tschucki\FilamentWorkflows\Models\FilamentWorkflow;
use Tschucki\FilamentWorkflows\Resources\FilamentWorkflowResource\Pages;

class FilamentWorkflowResource extends Resource
{
    protected static ?string $model = FilamentWorkflow::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make()->schema([
                    Forms\Components\Tabs\Tab::make('Basic')->schema([
                        Forms\Components\TextInput::make('title')->autofocus()->required(),
                        Forms\Components\Toggle::make('enabled')->inline(false)->default(true)->required(),
                        Forms\Components\Textarea::make('description')->nullable(),
                    ]),
                    Forms\Components\Tabs\Tab::make('Trigger')->schema([
                        Forms\Components\Select::make('trigger_type')->label('Trigger Type'),
                    ]),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('enabled')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('description')->searchable()->hidden(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFilamentWorkflows::route('/'),
            'create' => Pages\CreateFilamentWorkflow::route('/create'),
            'edit' => Pages\EditFilamentWorkflow::route('/{record}/edit'),
        ];
    }
}
