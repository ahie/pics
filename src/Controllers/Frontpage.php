<?php

namespace Pics\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pics\Template\Renderer;
use Pics\Repositories\PicRepositoryInterface;

class Frontpage extends BaseController
{
	private $repository;

        public function __construct(
                Request $request,
                Response $response,
                Renderer $renderer,
		PicRepositoryInterface $repository) {

                parent::__construct($request, $response, $renderer);
		$this->repository = $repository;

        }

	public function show() {
		$pics = $this->repository->fetchNewestPics();
		$html = $this->renderer->render('Frontpage', array('pics' => $pics));
		$this->response->setContent($html);
		$this->response->send();
	}
}
