<?php

namespace Tschucki\FilamentWorkflows\Observers;

use Illuminate\Database\Eloquent\Model;
use Tschucki\FilamentWorkflows\Models\Workflow;

class ModelObserver
{
    private function processWorkflows(Model $model, string $event): void
    {
        if (! method_exists($model, 'getWorkflows')) {
            return;
        }

        $workflows = $model->getWorkflows();

        $workflows
            ->filter(fn (Workflow $workflow) => $workflow->trigger[0]['event'] === $event)
            ->each(function (Workflow $workflow) use ($model) {
                if ($workflow->shouldTrigger($model)) {
                    $workflow->runActions($model);
                }
            });
    }

    public function created(Model $model): void
    {
        $this->processWorkflows($model, 'created');
    }

    public function updated(Model $model): void
    {
        $this->processWorkflows($model, 'updated');
    }

    public function deleted(Model $model): void
    {
        $this->processWorkflows($model, 'deleted');
    }

    public function restored(Model $model): void
    {
        $this->processWorkflows($model, 'restored');
    }

    public function forceDeleted(Model $model): void
    {
        $this->processWorkflows($model, 'forceDeleted');
    }
}
