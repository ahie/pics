<?php

namespace Pics\Storage;

interface FileStorageInterface
{
	public function store($file, $id);
}
