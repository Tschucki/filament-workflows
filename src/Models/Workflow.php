<?php

namespace Tschucki\FilamentWorkflows\Models;

use Illuminate\Database\Eloquent\Model;
use Tschucki\FilamentWorkflows\WorkflowActions\BaseAction;

class Workflow extends Model
{
    protected $table = 'filament_workflows';

    protected $casts = [
        'enabled' => 'boolean',
        'trigger' => 'array',
        'actions' => 'array',
    ];

    protected $guarded = [];

    public function shouldTrigger(Model $model): bool
    {
        // All Triggers must return true
        return collect($this->trigger[0]['triggers'])->map(function ($trigger) use ($model) {
            $field = $trigger['field'];
            $operator = $trigger['operator'];
            $value = $trigger['value'];

            $modelValue = $model->{$field};

            return match ($operator) {
                '==' => $modelValue == $value,
                '===' => $modelValue === $value,
                '!=' => $modelValue != $value,
                '!==' => $modelValue !== $value,
                '>' => $modelValue > $value,
                '<' => $modelValue < $value,
                '>=' => $modelValue >= $value,
                '<=' => $modelValue <= $value,
                default => false,
            };
        })->filter(fn ($trigger) => $trigger === true)->count() === count($this->trigger[0]['triggers']);
    }

    public function runActions(Model $model): void
    {
        collect($this->actions)->each(function ($action) use ($model) {
            $actionClass = $action['action_class'];
            /**
             * @var BaseAction $actionClass
             * */
            $actionClass = app($actionClass);

            $actionClass::dispatch($model);
        });
    }
}
