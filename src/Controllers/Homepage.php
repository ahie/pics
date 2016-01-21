<?php

namespace Pics\Controllers;

class Homepage extends BaseController
{
	public function show() {
		$data = [ 'name' => 'asdf' ];
		$html = $this->renderer->render('Homepage', $data);
		$this->response->setContent($html);
		$this->response->send();
	}
}
