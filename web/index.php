<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../app/AppKernel.php";

use Symfony\Component\HttpFoundation\Request;

if (file_exists(__DIR__ . $_SERVER['REQUEST_URI']) &&
    is_file(__DIR__ . $_SERVER['REQUEST_URI'])) {
    // Do not try to server static files – this is only important, if used
    // together with PHPs internal webserver.
    return false;
}

$request = Request::createFromGlobals();
$kernel = new AppKernel(AppKernel::getEnvironmentFromConfiguration(), AppKernel::getDebug());
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
