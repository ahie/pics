<?php

namespace Pics\Repositories;

use Pics\Models\Picture;

interface PicRepositoryInterface
{
	public function find($id);
	public function save(Picture $pic);
	public function remove(Picture $pic);
}

