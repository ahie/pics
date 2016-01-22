<?php

namespace Pics\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pics\Template\Renderer;
use Pics\Repositories\CommentRepositoryInterface;

class Comment
{

        private $request;
        private $response;
        private $renderer;
	private $repository;

	public function __construct(
                Request $request,
                Response $response,
                Renderer $renderer,
		CommentRepositoryInterface $repository) {

                $this->request = $request;
                $this->response = $response;
                $this->renderer = $renderer;
		$this->repository = $repository;

	}

	public function comment($params) {
		$pictureId = $params['pid'];
		$content = $this->request->get('text');

                if($content === '') {
                        $this->badRequest();
                        return;
                }

		$comment = new \Pics\Models\Comment;
		$comment->content = $this->request->get('text');
		$comment->picture = $pictureId;

		$cid = $this->repository->save($comment);
		$this->success($pictureId, $cid);
	}

	public function reply($params) {
		$parentCommentId = $params['cid'];
		$parentComment = $this->repository->find($parentCommentId);
		$content = $this->request->get('text');

		if($content === '' || !$parentComment) {
			$this->badRequest();
			return;
		}

		$comment = new \Pics\Models\Comment;
		$comment->content = $this->request->get('text');
		$comment->picture = $parentComment->picture;
		$comment->parent = $parentComment->id;
	
		$cid = $this->repository->save($comment);
		$this->success($parentComment->picture, $cid);
	}

	private function success($redirect, $cid) {
		if($this->request->isXmlHttpRequest()) {
			$this->response->setContent(json_encode(array('cid' => $cid)));
			$this->response->headers->set('Content-Type', 'application/json');
			$this->response->setStatusCode(Response::HTTP_CREATED);
			$this->response->send();
		} else {
			$this->response->headers->set('Location', '/' . $redirect);
			$this->response->send();
		}
	}

	private function badRequest() {
                if($this->request->isXmlHttpRequest()) {
                        $this->response->setStatusCode(Response::HTTP_BAD_REQUEST);
                        $this->response->send();
                } else {
			$error = array('code' => 400, 'message' => 'Can\'t leave an empty comment!');
			$html = $this->renderer->render('Error', $error);
                        $this->response->setStatusCode(Response::HTTP_BAD_REQUEST);
			$this->response->setContent($html);
                        $this->response->send();
                }
	}
}
