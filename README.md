# Laravel CSR
CSR stands for Controller/Service/Repository. This package lets you set all that up with just a single command, with the additional options to disable one of the three layers or even generate a model and/or migration automatically aswell.

## Usage
### Installation

Install the package using composer:
```
composer require scrumble-nl/laravel-csr
```

Publish the configuration file for default class paths:
```
php artisan vendor:publish --tag=laravel-csr
```

It is possible to modify the default class paths in this newly created `csr.php` config file.

### Command usage

The base command is `php artisan csr:gen {name} {namespace (optional)}`.


This will generate a controller, service interface, service, repository interface and repository all at once. They automatically are dependency injected into eachother so they are ready for usage immediately. Nice!

Finally, you need to add the generated service and/or repository to your `AppServiceProvider.php`.


#### Example

`php artisan csr:gen picture holiday`
will generate the following files:

- `app/Http/Controllers/Holiday/PictureController.php`
- `app/Interfaces/Services/Holiday/IPictureService.php`
- `app/Services/Holiday/PictureService`
- `app/Interfaces/Repositories/Holiday/IPictureRepository`
- `app/Repositories/Holiday/PictureRepository`

**Note**: The command will automatically capitalize the first character, so `picture` will become `Picture`. If you want your classes to have a name like `PictureBook` you will have to type this correctly yourself.

Now you will need to register your service and repository:

AppServiceProvider.php:
```php
<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Holiday\PictureService;
use App\Repositories\Holiday\PictureRepository;
use App\Interfaces\Services\Holiday\IPictureService;
use App\Interfaces\Repositories\Holiday\IPictureRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        app()->bind(IPictureService::class, PictureService::class);
        app()->bind(IPictureRepository::class, PictureRepository::class);
    }
}

```

And it's done!

#### Example output


app/Http/Controllers/Holiday/PictureController.php:
```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Holiday;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\Holiday\IPictureService;

class PictureController extends Controller
{
    /**
     * @var IPictureService
     */
    private $pictureService;

    /**
     * @param IPictureService $pictureService
     */
    public function __construct(IPictureService $pictureService)
    {
        $this->pictureService = $pictureService;
    }
}
```

### Command options

The following options can be used to disable some generation, or even add models and migrations to the command:

| Name         | Type                                                                                   | Required | Description                         | Default |
|--------------|:----------------------------------------------------------------------------------------:|:----------:|:-------------------------------------| -------- |
| `name`      | string                                                                                 | *true*     | The base name for all items to generate           |  |
| `namespace`       | string                                                                                 | *false*    | The namespace items will be in            | 
| `--model`      |  | *false*    | Automatically create a model aswell                      | `false`
| `--migration` |                                                                                  | *false*    | Automatically create a migration aswell | `false`
| `--policy` |                                                                                  | *false*    | Automatically create a policy aswell | `false`
| `--nc` |                                                                                  | *false*    | Do not generate the controller | `false`
| `--ns` |                                                                                  | *false*    | Do not generate the service and service interface | `false`
| `--nr` |                                                                                  | *false*    | Do not generate the repository and repository interface | `false`

See also: `php artisan csr:gen --help`

## Contributing
If you would like to see additions/changes to this package you are always welcome to add some code or improve it.

## Scrumble
This product has been originally developed by [Scrumble](https://www.scrumble.nl) for internal use. As we have been using lots of open source packages we wanted to give back to the community. We hope this helps you getting forward as much as other people helped us!
