<?php

namespace Tschucki\FilamentWorkflows\Support;

use Illuminate\Support\Str;


class Utils
{
    public static function getWorkflowModel(): string
    {
        return config('workflows.register.models.workflow', \Tschucki\FilamentWorkflows\Models\Workflow::class);
    }

    public static function getWorkflowLogModel(): string
    {
        return config('workflows.register.models.workflow_log', \Tschucki\FilamentWorkflows\Models\WorkflowLog::class);
    }

    public static function getWorkflowResource(): string
    {
        return config('workflows.register.resources.workflow', \Tschucki\FilamentWorkflows\Resources\FilamentWorkflowResource::class);
    }
}
