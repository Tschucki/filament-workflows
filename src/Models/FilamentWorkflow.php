<?php

namespace Tschucki\FilamentWorkflows\Models;

use Illuminate\Database\Eloquent\Model;

class FilamentWorkflow extends Model
{
    protected $table = 'filament_workflows';

    protected $casts = [
        'enabled' => 'boolean',
    ];

    protected $guarded = [];
}
