<?php

namespace Pics\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pics\Template\Renderer;
use Pics\Repositories\CommentRepositoryInterface;

class Comment extends BaseController
{

	private $repository;

	public function __construct(
                Request $request,
                Response $response,
                Renderer $renderer,
		CommentRepositoryInterface $repository) {

                parent::__construct($request, $response, $renderer);
		$this->repository = $repository;

	}

	public function comment($params) {
		$pictureId = $params['pid'];
		$content = $this->request->get('text');

                if($content === '' || strlen($content) > 15000) {
                        $this->errorResponse(400, 'Comment too long/empty');
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

		if($content === '' || strlen($content) > 15000) {
			$this->errorResponse(400, 'Comment too long/empty');
			return;
		}

		if(!$parentComment) {
			$this->errorResponse(400, 'Not a reply to any existing comment');
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

}
