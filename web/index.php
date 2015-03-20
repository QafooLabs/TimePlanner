<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../app/AppKernel.php";

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$kernel = new AppKernel(AppKernel::getEnvironmentFromConfiguration(), AppKernel::getDebug());
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
