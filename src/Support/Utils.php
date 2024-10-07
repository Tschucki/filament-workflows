<?php

namespace Tschucki\FilamentWorkflows\Support;

use Tschucki\FilamentWorkflows\Models\Workflow;
use Tschucki\FilamentWorkflows\Models\WorkflowLog;
use Tschucki\FilamentWorkflows\Resources\FilamentWorkflowResource;

class Utils
{
    public static function getWorkflowModel(): string
    {
        return config('workflows.models.workflow', Workflow::class);
    }

    public static function getWorkflowLogModel(): string
    {
        return config('workflows.models.workflow_log', WorkflowLog::class);
    }

    public static function getWorkflowResource(): string
    {
        return config('workflows.resources.workflow', FilamentWorkflowResource::class);
    }
}
