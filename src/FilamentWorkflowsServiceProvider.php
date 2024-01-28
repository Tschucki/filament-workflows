<?php

namespace Tschucki\FilamentWorkflows;

use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Tschucki\FilamentWorkflows\Concerns\CanSetupWorkflows;
use Tschucki\FilamentWorkflows\Concerns\InteractsWithWorkflow;
use Tschucki\FilamentWorkflows\Observers\ModelObserver;
use Tschucki\FilamentWorkflows\Testing\TestsFilamentWorkflows;

class FilamentWorkflowsServiceProvider extends PackageServiceProvider
{
    use CanSetupWorkflows;

    public static string $name = 'filament-workflows';

    public static string $viewNamespace = 'filament-workflows';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('tschucki/filament-workflows');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
    }

    public function boot(): void
    {
        parent::boot();
        $this->registerGlobalObservers();
    }

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-workflows/{$file->getFilename()}"),
                ], 'filament-workflows-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentWorkflows());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'tschucki/filament-workflows';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-workflows', __DIR__ . '/../resources/dist/components/filament-workflows.js'),
            Css::make('filament-workflows-styles', __DIR__ . '/../resources/dist/filament-workflows.css'),
            Js::make('filament-workflows-scripts', __DIR__ . '/../resources/dist/filament-workflows.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_filament-workflow_tables',
        ];
    }

    protected function registerGlobalObservers(): void
    {
        self::getAllModelsWithTrait(InteractsWithWorkflow::class)->each(function ($model) {
            $model::observe(ModelObserver::class);
        });
    }
}
