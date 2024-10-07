<?php

namespace Tschucki\FilamentWorkflows\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Tschucki\FilamentWorkflows\Support\Utils;

trait InteractsWithWorkflow
{
    public function workflowLogs(): MorphMany
    {
        return $this->morphMany(Utils::getWorkflowLogModel(), 'model');
    }

    protected function workflows(): MorphMany
    {
        return $this->morphMany(Utils::getWorkflowModel(), 'model');
    }

    public function getWorkflows()
    {
        $model = Utils::getWorkflowModel();

        if ($this->model_id === null) {
            return (new $model)::where('model_id', null)->where('model_type', self::class)->get();
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

    public function getTitleColumnForWorkflowSearch(): ?string
    {
        return null;
    }
}
