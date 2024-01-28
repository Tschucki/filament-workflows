# Add workflows to your filament app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tschucki/filament-workflows.svg?style=flat-square)](https://packagist.org/packages/tschucki/filament-workflows)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/tschucki/filament-workflows/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/tschucki/filament-workflows/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Fix PHP Code Styling](https://github.com/Tschucki/filament-workflows/actions/workflows/fix-php-code-styling.yml/badge.svg)](https://github.com/Tschucki/filament-workflows/actions/workflows/fix-php-code-styling.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/tschucki/filament-workflows.svg?style=flat-square)](https://packagist.org/packages/tschucki/filament-workflows)

This plugin lets you add workflows to your filament app. You can attach triggers and dispatchable actions to your
workflows. The plugin will automatically execute the actions when the trigger conditions are met.

## Images

![Screenshot 1](.github/images/Basic-Form.png)
![Screenshot 2](.github/images/Trigger-Form.png)
![Screenshot 3](.github/images/Actions-Form.png)

## Installation

You can install the package via composer:

```bash
composer require tschucki/filament-workflows
```

You can install the plugin using:

```bash
php artisan filament-workflows:install
```

You can publish and run the migrations manually with:

```bash
php artisan vendor:publish --tag="filament-workflows-migrations"
php artisan migrate
```

Register the plugin in your `AdminPanelServiceProvider`:

```php
use Tschucki\FilamentWorkflows\FilamentWorkflowsPlugin;

->plugins([
    FilamentWorkflowsPlugin::make()
])
```

## Usage
### Basics
In order to let your models use workflows, you need to add the `InteractsWithWorkflows` trait to your model. By adding this trait, the plugin will automatically add a global observer to your model. So when ever a workflow matches the event and trigger conditions, the workflow will execute the actions.

### Add the trait to your model
```php
use Tschucki\FilamentWorkflows\Concerns\InteractsWithWorkflow;

class User extends Model {
  use InteractsWithWorkflow;
}
```

### Create a Action
In order to attach an action to your workflows, you will have to create a class within the `App\Jobs\Actions` folder. The class must extend the `BaseAction` class. This requires you to implement the `handle` method. This method will be called when the workflow is executed.

The action class is very similar to a job.
When ever the action get executed, the model will be passed to the `__construct` method. You can use the model to do whatever you want.

The plugin will find this class on its own. So you don't have to register it anywhere.

```php
<?php

namespace App\Jobs\WorkflowActions;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Tschucki\FilamentWorkflows\WorkflowActions\BaseAction;

class TestAction extends BaseAction
{
    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(): void
    {
        \Log::info($this->user->name . ' was created at ' . $this->user->created_at);
    }
}
```

That's it. Now you can create and attach actions to your workflows.


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Marcel Wagner](https://github.com/Tschucki)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
