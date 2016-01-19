<?php

$injector = new \Auryn\Injector;

// Http request and response
$injector->share('Symfony\Component\HttpFoundation\Request');
$injector->define('Symfony\Component\HttpFoundation\Request', [
	':query' => $_GET,
	':request' => $_POST,
	':attributes' => array(),
	':cookies' => $_COOKIE,
	':files' => $_FILES,
	':server' => $_SERVER,
]);
$injector->share('Symfony\Component\HttpFoundation\Response');

// Templating engine
$injector->alias('Pics\Template\Renderer', '\Pics\Template\MustacheRenderer');
$injector->define('Mustache_Engine',
[ ':options' => [
	'cache' => '/tmp/cache/mustache',
	'loader' => new Mustache_Loader_FilesystemLoader(dirname(__DIR__) . '/templates', [
		'extension' => '.html'
	])
]]);

return $injector;

