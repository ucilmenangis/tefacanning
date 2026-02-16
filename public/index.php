<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Detect Laravel Base Path (Local vs cPanel Hosting)
|--------------------------------------------------------------------------
|
| On cPanel hosting, the project is split: public files go to public_html/
| and Laravel core goes to ~/laravel/. This auto-detects the correct path.
|
*/

$basePath = file_exists(__DIR__.'/../laravel/bootstrap/app.php')
    ? __DIR__.'/../laravel'
    : __DIR__.'/..';

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
*/

if (file_exists($maintenance = $basePath.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
*/

require $basePath.'/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
*/

$app = require_once $basePath.'/bootstrap/app.php';

// Set the public path to this directory (required for cPanel split layout)
$app->bind('path.public', function() {
    return __DIR__;
});

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
