<?php

return [
    'register' => [
        'models' => [
            'workflow' => \Tschucki\FilamentWorkflows\Models\WorkflowLog::class,
            'workflow_log' => \Tschucki\FilamentWorkflows\Models\WorkflowLog::class,
        ],
        'resources' => [
            'workflow' => \Tschucki\FilamentWorkflows\Resources\FilamentWorkflowResource::class,
        ],
    ],

    'search' => [
        'max_results' => 100,
    ],
];
