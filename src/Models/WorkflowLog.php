<?php

namespace Tschucki\FilamentWorkflows\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowLog extends Model
{
    protected $table = 'filament_workflow_logs';

    protected $guarded = [];

    public function workflow(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }
}
