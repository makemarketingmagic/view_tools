# View tools for Laravel 11.

Easy creation of html horror shows in Laravel.
> **NOTE:** parental discretion advised

* [Installation](#installation)
* [Use](#use)


## Installation
Add the package service provider to config/app.php inside the providers array.
```
  'providers' => [
      ...
      /*
      * Package Service Providers...
      */
      ...
      Makemarketingmagic\ViewTools\ViewToolsServiceProvider::class
  ]
```

For package development, add our namespace to composer's autoloader:
```
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            ...
            "Makemarketingmagic\\ViewTools\\": "packages/makemarketingmagic/view_tools/src/"
        },
        ...
    },
```

Publish our views only:
```
php artisan vendor:publish --provider="Makemarketingmagic\ViewTools\ViewToolsServiceProvider" --tag=views
```

Publish our config only:
```
php artisan vendor:publish --provider="Makemarketingmagic\ViewTools\ViewToolsServiceProvider" --tag=config
```

Or publish all:
```
php artisan vendor:publish --provider="Makemarketingmagic\ViewTools\ViewToolsServiceProvider"
```

## Use
```
Not sure yet, but ......
    $tableBuilder = new ModelTableBuilder();
    $table = $tableBuilder
        ->query($model)
        ->toTable();
    return view('some_view', ['table' => $table]);
... and so on...
```
