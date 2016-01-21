<?php

namespace Pics\Controllers;

use Pics\Repositories\PicRepositoryInterface;
use Pics\Storage\FileStorageInterface;

class Upload extends BaseController
{

	private $repository;
	private $fileStorage;

	const mimeRegex = '/^image\//';

	public function __construct(
		PicRepositoryInterface $repository,
		FileStorageInterface $fileStorage) {

		$this->repository = $repository;
		$this->fileStorage = $fileStorage;

	}

	public function upload() {

		$file = $this->request->files->get('userfile');

		if(!$file->isValid()) {
			echo 'error: ' . $file->getError();
		}
		if(!preg_match(self::mimeRegex, $file->getMimeType())) {
			echo 'wrong filetype.';
		}

		$picid = $this->repository->save();
		$this->fileStorage->store($file, $picid);

		echo 'done';

	}
}
