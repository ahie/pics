<?php

namespace Pics\Template;

interface Renderer
{
	public function render($template, $data = []);	
}
