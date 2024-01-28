<?php

namespace Tschucki\FilamentWorkflows\Concerns;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Tschucki\FilamentWorkflows\WorkflowActions\BaseAction;

trait CanSetupWorkflows
{
    public static function getAllModels(): Collection
    {
        $models = collect(File::allFiles(app_path()))
            ->map(function ($item) {
                $path = $item->getRelativePathName();

                return sprintf(
                    '\%s%s',
                    Container::getInstance()->getNamespace(),
                    str_replace('/', '\\', substr($path, 0, strrpos($path, '.')))
                );
            })
            ->filter(function ($class) {
                $valid = false;

                if (class_exists($class)) {
                    $reflection = new \ReflectionClass($class);
                    $valid = $reflection->isSubclassOf(Model::class) &&
                        ! $reflection->isAbstract();
                }

                return $valid;
            })->map(function ($class) {
                return app($class);
            });

        return $models->values();
    }

    public static function getAllActions(): Collection
    {
        $actions = collect(File::allFiles(app_path()))
            ->map(function ($item) {
                $path = $item->getRelativePathName();

                return sprintf(
                    '\%s%s',
                    Container::getInstance()->getNamespace(),
                    str_replace('/', '\\', substr($path, 0, strrpos($path, '.')))
                );
            })
            ->filter(function ($class) {
                $valid = false;

                if (class_exists($class)) {
                    $reflection = new \ReflectionClass($class);
                    $valid = $reflection->isSubclassOf(BaseAction::class) &&
                        ! $reflection->isAbstract();
                }

                return $valid;
            })->map(function ($class) {
                return app($class);
            });

        return $actions->values();
    }

    public static function getAllModelsWithTrait(string $trait): Collection
    {
        return self::getAllModels()->filter(fn ($model) => self::modelUsesTrait($model, $trait));
    }

    public static function modelUsesTrait(Model $model, string $trait): bool
    {
        return array_key_exists($trait, (new \ReflectionClass($model))->getTraits());
    }

    public static function getModelTypeOptions()
    {
        return self::getAllModelsWithTrait(InteractsWithWorkflow::class)->mapWithKeys(function ($model, $key) {
            return [
                $model::class => $model->getModelTitelForWorkflow(),
            ];
        });
    }

    public static function getActionOptions()
    {
        return self::getAllActions()->mapWithKeys(function (BaseAction $action, $key) {
            return [
                $action::class => $action->getActionName(),
            ];
        });
    }

    public static function getModelOptions(?Model $model)
    {
        if (! $model) {
            return [];
        }

        if (self::modelUsesTrait($model, InteractsWithWorkflow::class)) {
            return $model::all()->mapWithKeys(function ($model, $key) {
                return [
                    $model->getKey() => $model->getTitelAttributeForWorkflow(),
                ];
            });
        }

        throw new \InvalidArgumentException(sprintf('Model %s does not use trait %s', $model::class, InteractsWithWorkflow::class));
    }

    public static function getModelFields(?Model $model): array
    {
        if (! $model) {
            return [];
        }

        if (self::modelUsesTrait($model, InteractsWithWorkflow::class)) {
            $table = $model->getTable();

            return collect(\Schema::getColumnListing($table))->mapWithKeys(function ($column) {
                return [
                    $column => $column,
                ];
            })->toArray();
        }

        throw new \InvalidArgumentException(sprintf('Model %s does not use trait %s', $model::class, InteractsWithWorkflow::class));
    }
}
