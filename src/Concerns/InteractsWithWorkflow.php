<?php

namespace Tschucki\FilamentWorkflows\Concerns;

use Tschucki\FilamentWorkflows\Models\Workflow;
use Tschucki\FilamentWorkflows\Models\WorkflowLog;

trait InteractsWithWorkflow
{
    public function workflowLogs(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(WorkflowLog::class, 'model');
    }

    protected function workflows(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Workflow::class, 'model');
    }

    public function getWorkflows()
    {
        if ($this->model_id === null) {
            return Workflow::where('model_id', null)->where('model_type', self::class)->get();
        }

        return $this->workflows;
    }

    public function getModelTitelForWorkflow(): string
    {
        return class_basename(self::class);
    }

    public function getTitelAttributeForWorkflow()
    {
        return $this->getKey();
    }

    public function workflowHasBeenSetup(): bool
    {
        $cWorkflows = $this->workflows();
        $workflowExists = $cWorkflows->exists();
        $workflowIsEnabled = $this->workflows()->enabled;
        $workflowModelId = ! $this->workflows()->model_id || $this->workflow()->model_id === $this->getKey();

        return $workflowExists && $workflowIsEnabled && $workflowModelId;
    }
}
