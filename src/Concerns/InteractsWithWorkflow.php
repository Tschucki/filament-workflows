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

        //IF I ASSIGN A MODEL_ID TO A WORKFLOW FIRST WAS NOT WORKING FOR ME.

        //this is the edit
        if ($this->workflows()->exists()) {
            return $this->workflows()->get();
        }
        else {
            return Workflow::where('model_id', null)->where('model_type', self::class)->get();
        }

        //THIS RETURN NULL ALSO IF I ASSIGN A MODEL_ID TO THE WORKFLOW
        // if ($this->model_id === null) {
        //     return Workflow::where('model_id', null)->where('model_type', self::class)->get();
        // }

        // return $this->workflows;
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

    public function getTitleColumnForWorkflowSearch(): ?string
    {
        return null;
    }
}
