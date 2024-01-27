<?php

namespace Tschucki\FilamentWorkflows\Commands;

use Illuminate\Console\Command;

class FilamentWorkflowsCommand extends Command
{
    public $signature = 'filament-workflows';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
