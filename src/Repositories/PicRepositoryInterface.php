<?php

namespace Pics\Repositories;

interface PicRepositoryInterface
{
	public function find($id);
	public function save(Picture $pic);
	public function remove(Picture $pic);
}

