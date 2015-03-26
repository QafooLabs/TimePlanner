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

try {
    if (AppKernel::getDebug()) {
        fwrite(
            fopen('php://stderr', 'a'),
            sprintf(
                "[%s] [Symfony]: %s %s\n",
                date('D M d H:i:s Y'),
                $_SERVER['REQUEST_METHOD'],
                $_SERVER['REQUEST_URI']
            )
        );
    }

    $request = Request::createFromGlobals();
    $kernel = new AppKernel(AppKernel::getEnvironmentFromConfiguration(), AppKernel::getDebug());
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
} catch (\Exception $e) {
    header("HTTP/1.0 500 Internal Server Error");
    echo "<html><body><h1>Internal Server Error</h1>";
    if (AppKernel::getDebug()) {
        echo '<pre style="white-space: pre-wrap;">', $e, '</pre>';
    }
    echo "</body></html>";
}
