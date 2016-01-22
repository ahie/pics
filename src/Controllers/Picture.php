<?php

namespace Pics\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pics\Template\Renderer;
use Pics\Repositories\PicRepositoryInterface;
use Pics\Repositories\CommentRepositoryInterface;

class Picture
{
        private $request;
        private $response;
        private $renderer;
        private $picRepository;
	private $commRepository;

        public function __construct(
                Request $request,
                Response $response,
                Renderer $renderer,
                PicRepositoryInterface $picRepository,
		CommentRepositoryInterface $commRepository) {

                $this->request = $request;
                $this->response = $response;
                $this->renderer = $renderer;
                $this->picRepository = $picRepository;
		$this->commRepository = $commRepository;

        }

	public function show($params) {
		$pic = $this->picRepository->find($params['id']);
		if(!$pic) {
			$data = array('code' => 404, 'message' => 'Image not found!');
			$html = $this->renderer->render('Error', $data);
			$this->response->setStatusCode(Response::HTTP_NOT_FOUND);
			$this->response->setContent($html);
			$this->response->send();
			return;
		}
		$comments = $this->commRepository->fetchAllCommentsForPic($params['id']);
		$data = array('pic' => $pic, 'comments' => $comments);
		$html = $this->renderer->render('Picture', $data);
		$this->response->setContent($html);
		$this->response->send();
	}

}
