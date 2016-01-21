<?php

namespace Pics\Storage;

use WindowsAzure\Common\ServicesBuilder;
use WindowsAzure\Common\ServiceException;
use WindowsAzure\Blob\Models\CreateBlobOptions;

class AzureFileStorage implements FileStorageInterface
{

	private $blobRestProxy;
	private $container;

	public function __construct($connectionString, $container) {
		$this->blobRestProxy = ServicesBuilder::getInstance()
					->createBlobService($connectionString);
		$this->container = $container;
	}

	public function store($file, $id) {
		$content = fopen($file->getRealPath(), 'r');
		$blobOpts = new CreateBlobOptions;
		$blobOpts->setContentType($file->getMimeType());
		$this->blobRestProxy->createBlockBlob($this->container, $id, $content, $blobOpts);
	}
}
