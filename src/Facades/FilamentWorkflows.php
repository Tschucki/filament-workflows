<?php

namespace Tschucki\FilamentWorkflows\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Tschucki\FilamentWorkflows\FilamentWorkflows
 */
class FilamentWorkflows extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Tschucki\FilamentWorkflows\FilamentWorkflows::class;
    }
}
