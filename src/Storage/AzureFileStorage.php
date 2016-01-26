<?php

namespace Pics\Storage;

use WindowsAzure\Common\ServicesBuilder;
use WindowsAzure\Common\ServiceException;
use WindowsAzure\Blob\Models\CreateBlobOptions;

class AzureFileStorage implements FileStorageInterface
{

	private $blobRestProxy;
	private $container;
	private $url;

	public function __construct($connectionString, $container, $url) {
		$this->blobRestProxy = ServicesBuilder::getInstance()
					->createBlobService($connectionString);
		$this->container = $container;
		$this->url = $url;
	}

	public function store($file, $id) {
		$blobOpts = new CreateBlobOptions;
		$blobOpts->setContentType($file->getMimeType());
		$blobOpts->setCacheControl('max-age=315360000');

		try {
			$img = new \Imagick($file->getRealPath());
			$this->blobRestProxy->createBlockBlob($this->container, $id, $img->getImagesBlob(), $blobOpts);

			$img->coalesceImages();
			foreach ($img as $frame) {
				$frame->thumbnailImage(180, 180);
				$frame->setImagePage(180, 180, 0, 0);
			}
			$this->blobRestProxy->createBlockBlob($this->container, $id . '.t', $img->getImagesBlob(), $blobOpts);
		}
		catch (\Exception $e) {
			return $e;
		}
		return null;
	}

	public function getUrl() {
		return $this->url;
	}
}
