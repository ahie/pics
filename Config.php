<?php

$config = array(

	'environment' => 'development',

	'database' => array(
	        'dsn' => 'pgsql:dbname=picdb;host=localhost',
	        'username' => 'picuser',
        	'password' => 'keyboardcat'
	),

	'azureConnString' => 'DefaultEndpointsProtocol=http;AccountName=picss;AccountKey=9oGzUNHj+AYCOTobnguwjVIoYidTNsGvpfZAkcDIqkFCgCoBsCLJ1NqZdNwfgFLE99ZxlXKxX1GUDE0hmHfSpQ==',
	'azureBlobContainer' => 'pics'

);
