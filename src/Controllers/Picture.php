<?php

namespace Pics\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pics\Template\Renderer;
use Pics\Repositories\PicRepositoryInterface;

class Picture
{
        private $request;
        private $response;
        private $renderer;
        private $repository;

        public function __construct(
                Request $request,
                Response $response,
                Renderer $renderer,
                PicRepositoryInterface $repository) {

                $this->request = $request;
                $this->response = $response;
                $this->renderer = $renderer;
                $this->repository = $repository;

        }

	public function show($id) {
		$pic = $this->repository->find($id);
		$html = $this->renderer->render('Picture', $pic);
		$this->response->setContent($html);
		$this->response->send();
	}

}
