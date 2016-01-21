<?php

namespace Pics;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ERROR | E_WARNING | E_PARSE);

$environment = 'development';

$whoops = new \Whoops\Run;
if ($environment !== 'production') {
	$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
	$whoops->pushHandler(function($e){
		echo 'Friendly error page and send an email to the developer';
	});
}
$whoops->register();

$injector = include('Dependencies.php');

$request = $injector->make('Symfony\Component\HttpFoundation\Request');
$response = $injector->make('Symfony\Component\HttpFoundation\Response');

$routeDefCallback = function (\FastRoute\RouteCollector $r) {
    $routes = include('Routes.php');
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
};
$dispatcher = \FastRoute\simpleDispatcher($routeDefCallback);

$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());
switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
	$response->setContent('404');
	$response->headers->set('Content-Type', 'text/plain');
	$response->setStatusCode(Response::HTTP_NOT_FOUND);
	$response->send();
        break;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
	$response->setContent('405');
	$response->headers->set('Content-Type', 'text/plain');
	$response->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED);
	$response->send();
        break;
    case \FastRoute\Dispatcher::FOUND:
        $className = $routeInfo[1][0];
        $method = $routeInfo[1][1];
	$vars = $routeInfo[2];
	$injector->make($className)->$method($vars);
        break;
}
