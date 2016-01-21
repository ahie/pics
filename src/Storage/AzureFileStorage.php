<?php

namespace Pics\Storage;

use WindowsAzure\Common\ServicesBuilder;
use WindowsAzure\Common\ServiceException;
use WindowsAzure\Blob\Models\CreateBlobOptions;

class AzureFileStorage implements FileStorageInterface
{

	private $blobRestProxy;

	public function __construct($connectionString) {
		$this->blobRestProxy = ServicesBuilder::getInstance()
					->createBlobService($connectionString);
	}

	public function store(File $file, $id) {
		$content = fopen($file->getRealPath(), 'r');
		$blobName = $id;
		$blobOpts = new CreateBlobOptions;
		$blobOpts->setContentType($file->getMimeType());
		$blobRestProxy->createBlockBlob("pics", $blob_name, $content, $blob_opts);
	}
}
