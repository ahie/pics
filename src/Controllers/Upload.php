<?php

namespace Pics\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pics\Template\Renderer;
use Pics\Repositories\PicRepositoryInterface;
use Pics\Storage\FileStorageInterface;

class Upload extends BaseController
{

	private $repository;
	private $fileStorage;

	const mimeRegex = '/^image\//';

	public function __construct(
                Request $request,
                Response $response,
                Renderer $renderer,
		PicRepositoryInterface $repository,
		FileStorageInterface $fileStorage) {

		parent::__construct($request, $response, $renderer);
		$this->repository = $repository;
		$this->fileStorage = $fileStorage;

	}

	public function upload() {

		$file = $this->request->files->get('userfile');

		if(!$file) {
			$this->errorResponse(400, 'No file uploaded');
			return;
		}
		if(!$file->isValid()) {
			$this->errorResponse(400, 'Not uploaded successfully');
			return;
		}
		if(!preg_match(self::mimeRegex, $file->getMimeType())) {
			$this->errorResponse(400, 'File must be an image');
			return;
		}

		$pic = new \Pics\Models\Picture;
		$pic->filesize = $file->getSize();
		$pic->url = $this->fileStorage->getUrl();

		$picid = $this->repository->save($pic);

		if(!$picid) {
			$this->errorResponse(500, 'Failed to add picture');
			return;
		}

		$err = $this->fileStorage->store($file, $picid);

		if(isset($err)) {
			$this->repository->remove($picid);
			$this->errorResponse(500, 'Failed to store picture');
			return;
		}

		$this->response->headers->set('Location', '/' . $picid);
		$this->response->send();
	}

}
