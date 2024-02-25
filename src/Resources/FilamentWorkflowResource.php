<?php

namespace Tschucki\FilamentWorkflows\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Tschucki\FilamentWorkflows\Concerns\CanSetupWorkflows;
use Tschucki\FilamentWorkflows\Models\Workflow;
use Tschucki\FilamentWorkflows\Resources\FilamentWorkflowResource\Pages;

class FilamentWorkflowResource extends Resource
{
    use CanSetupWorkflows;

    protected static ?string $model = Workflow::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make()->schema([
                    Forms\Components\Tabs\Tab::make('Basic')->schema([
                        Forms\Components\Select::make('model_type')->searchable()->reactive()->options(self::getModelTypeOptions())->required(),
                        Forms\Components\Select::make('model_id')->searchable()->getSearchResultsUsing(fn (Forms\Get $get, ?string $search) => $get('model_type') ? self::getModelOptions(app($get('model_type')), $search) : [])->nullable(),
                        Forms\Components\TextInput::make('title')->autofocus()->required(),
                        Forms\Components\Toggle::make('enabled')->inline(false)->default(true)->required(),
                        Forms\Components\Textarea::make('description')->nullable(),
                    ]),
                    Forms\Components\Tabs\Tab::make('Trigger')->schema([
                        Forms\Components\Repeater::make('trigger')->schema(fn (Forms\Get $get) => [
                            Forms\Components\Select::make('event')->label('Event')->options([
                                'created' => 'created',
                                'updated' => 'updated',
                                'deleted' => 'deleted',
                                'restored' => 'restored',
                                'forceDeleted' => 'forceDeleted',
                            ])->reactive()->required(),
                            Forms\Components\Group::make()->schema([
                                Forms\Components\Repeater::make('triggers')->schema([
                                    Forms\Components\Fieldset::make('Trigger Definition')->schema([
                                        Forms\Components\Select::make('field')->reactive()->label('Field')->options(fn () => $get('model_type') ? self::getModelFields(app($get('model_type'))) : [])->nullable(),
                                        Forms\Components\Select::make('operator')->label('Operator')->options([
                                            '===' => '===',
                                            '==' => '==',
                                            '!=' => '!=',
                                            '!==' => '!==',
                                            '>' => '>',
                                            '<' => '<',
                                            '>=' => '>=',
                                            '<=' => '<=',
                                        ])->nullable(),
                                        Forms\Components\TextInput::make('value')->label('Value')->nullable(),
                                    ])->columns(3),
                                ]),
                            ])->visible(fn (Forms\Get $get) => $get('event') !== 'deleted'),
                        ])->maxItems(1)->minItems(1)->columns(1),
                    ]),
                    Forms\Components\Tabs\Tab::make('Actions')->label('Actions')->schema([
                        Forms\Components\Repeater::make('actions')->schema([
                            Forms\Components\Select::make('action_class')->label('Action')->options(self::getActionOptions())->required(),
                        ])->columns(1),
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
