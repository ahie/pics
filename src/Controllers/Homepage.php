<?php

namespace Pics\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pics\Template\Renderer;

class Homepage
{

	private $request;
	private $response;
	private $renderer;

	public function __construct(
		Request $request,
		Response $response,
		Renderer $renderer) {

		$this->request = $request;
		$this->response = $response;
		$this->renderer = $renderer;

	}

	public function show() {
		$data = [ 'name' => 'asdf' ];
		$html = $this->renderer->render('Homepage', $data);
		$this->response->setContent($html);
		$this->response->send();
	}
}
