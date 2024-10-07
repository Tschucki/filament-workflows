<?php

namespace Tschucki\FilamentWorkflows;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Tschucki\FilamentWorkflows\Resources\FilamentWorkflowResource;
use Tschucki\FilamentWorkflows\Support\Utils;

class FilamentWorkflowsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-workflows';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            Utils::getWorkflowResource(),
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
