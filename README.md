Slim Framework Routes File Builder
==================================

### Information

This library automatically creates the `app/bootstrap.php` file for Slim Framework applications. This is controlled
in development and production by the `$scanAndWrite` parameter. 

If `$scanAndWrite` parameter is `true` this will rescan the routes directory (or directories) and generate the bootstrap
file automatically. If `false`, the library will simply return the path to the bootstrap file.

### Usage...

In your main `public/index.php` or wherever you currently include your routes file, place this:

```php
<?php
define('BASE_DIR', realpath(__DIR__ . '/../'));

$shouldRescan = true; // You could use a 'development' flag to establish this

$routeFile = \District5\SlimRouteFileBuilder\RouteBuilder::scanRoutes(
    $shouldRescan, // Should the system rescan and write a new routes file?
    BASE_DIR, // The base directory to work with.
    '-route.php' // What the route file names should end in (optional). Use null to ignore this.
);
/** @noinspection PhpIncludeInspection */
include $routeFile;
```
