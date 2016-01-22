<?php

namespace Pics;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Dependency injection
$injector = include('Dependencies.php');
$request = $injector->make('Symfony\Component\HttpFoundation\Request');
$response = $injector->make('Symfony\Component\HttpFoundation\Response');
$renderer = $injector->make('Pics\Template\Renderer');
$pdo = $injector->make('PDO');
$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
$m = $injector->make('Memcached');
$m->addServer('127.0.0.1', 11211);

// Error handler
if ($config['environment'] !== 'production') {
	$whoops = new \Whoops\Run;
	$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
	$whoops->register();
}

// Routing
$dispatcher = \FastRoute\simpleDispatcher(
function(\FastRoute\RouteCollector $r) {
    $routes = include('Routes.php');
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
});
$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());
switch ($routeInfo[0]) {
	case \FastRoute\Dispatcher::NOT_FOUND:
		$html = $renderer->render('Error', array(
		'code' => 404,
		'message' => 'Page not found :(')
		);
		$response->setStatusCode(Response::HTTP_NOT_FOUND);
		$response->setContent($html);
		$response->send();
		break;
	case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
		$html = $renderer->render('Error', array(
		'code' => 405,
		'message' => 'Method not allowed')
		);
		$response->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED);
		$response->setContent($html);
		$response->send();
		break;
	case \FastRoute\Dispatcher::FOUND:
		$className = $routeInfo[1][0];
		$method = $routeInfo[1][1];
		$vars = $routeInfo[2];
		$injector->make($className)->$method($vars);
		break;
}
