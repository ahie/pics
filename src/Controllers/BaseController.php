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

}
