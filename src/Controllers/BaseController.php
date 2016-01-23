<?php

namespace Pics\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pics\Template\Renderer;

class BaseController
{
	protected $request;
	protected $response;
	protected $renderer;

	public function __construct(
		Request $request,
		Response $response,
		Renderer $renderer) {

		$this->request = $request;
		$this->response = $response;
		$this->renderer = $renderer;

	}

	protected function errorResponse($code, $message = null) {

		if($this->request->isXmlHttpRequest) {
			$this->response->setStatusCode($code);
			if(isset($message)) {
				$this->response->setContent(json_encode(array(
					'message' => $message
				)));
				$this->response->headers->set('Content-Type', 'application/json');
			}
			$this->response->send();
		} else {
			$error = array('code' => $code, 'message' => $message);
			$html = $this->renderer->render('Error', $error);
			$this->response->setStatusCode($code);
			$this->response->setContent($html);
			$this->response->send();
		}
	}

}
