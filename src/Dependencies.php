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
$injector->alias('Pics\Template\Renderer', 'Pics\Template\MustacheRenderer');
$injector->define('Mustache_Engine',[ 
':options' => [
	'cache' => '/tmp/cache/mustache',
	'loader' => new Mustache_Loader_FilesystemLoader(dirname(__DIR__) . '/templates', [
		'extension' => '.html'
	])
]]);

// Database
$injector->share('PDO');
$injector->define('PDO', [
	':dsn' => $config['database']['dsn'],
	':username' => $config['database']['username'],
	':password' => $config['database']['password']
]);
$injector->alias('Pics\Repositories\PicRepositoryInterface', 'Pics\Repositories\PSQLPicRepository');

// File storage
$injector->share('Pics\Storage\AzureFileStorage');
$injector->define('Pics\Storage\AzureFileStorage', [
	':connectionString' => $config['azureConnString'], 
	':container' => $config['azureBlobContainer']
]);
$injector->alias('Pics\Storage\FileStorageInterface', 'Pics\Storage\AzureFileStorage');

return $injector;

