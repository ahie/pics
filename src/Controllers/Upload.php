<?php

namespace Pics\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pics\Template\Renderer;
use Pics\Repositories\PicRepositoryInterface;
use Pics\Storage\FileStorageInterface;

class Upload
{

        private $request;
        private $response;
        private $renderer;
	private $repository;
	private $fileStorage;

	const mimeRegex = '/^image\//';

	public function __construct(
                Request $request,
                Response $response,
                Renderer $renderer,
		PicRepositoryInterface $repository,
		FileStorageInterface $fileStorage) {

                $this->request = $request;
                $this->response = $response;
                $this->renderer = $renderer;
		$this->repository = $repository;
		$this->fileStorage = $fileStorage;

	}

	public function upload() {

		$file = $this->request->files->get('userfile');

		if(!$file->isValid()) {
			$error = array('code' => 400, 'message' => $file->getError());
			$html = $this->renderer->render('Error', $error);
			$this->response->setContent($html);
			$this->response->setStatusCode(Response::HTTP_BAD_REQUEST);
			$this->response->send();
			return;
		}
		if(!preg_match(self::mimeRegex, $file->getMimeType())) {
			$error = array('code' => 400, 'message' => 'File must be an image.');
			$html = $this->renderer->render('Error', $error);
			$this->response->setContent($html);
			$this->response->setStatusCode(Response::HTTP_BAD_REQUEST);
			$this->response->send();
			return;
		}

		$pic = new \Pics\Models\Picture;
		$pic->filesize = $file->getSize();
		$pic->url = $this->fileStorage->getUrl();

		$picid = $this->repository->save($pic);
		$this->fileStorage->store($file, $picid);

		$this->response->headers->set('Location', '/' . $picid);
		$this->response->send();

	}
}
