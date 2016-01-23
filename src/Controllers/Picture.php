<?php

namespace Pics\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pics\Template\Renderer;
use Pics\Repositories\PicRepositoryInterface;
use Pics\Repositories\CommentRepositoryInterface;

class Picture extends BaseController
{

        private $picRepository;
	private $commRepository;

        public function __construct(
                Request $request,
                Response $response,
                Renderer $renderer,
                PicRepositoryInterface $picRepository,
		CommentRepositoryInterface $commRepository) {

                parent::__construct($request, $response, $renderer);
                $this->picRepository = $picRepository;
		$this->commRepository = $commRepository;

        }

	public function show($params) {
		$pic = $this->picRepository->find($params['id']);
		if(!$pic) {
			$this->errorResponse(404, 'Image not found!');
			return;
		}
		$comments = $this->commRepository->fetchAllCommentsForPic($params['id']);
		$data = array('pic' => $pic, 'comments' => $comments);
		$html = $this->renderer->render('Picture', $data);
		$this->response->setContent($html);
		$this->response->send();
	}

}
