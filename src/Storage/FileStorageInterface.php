<?php

namespace Pics\Storage;

interface FileStorageInterface
{
	public function store(File $file, $id);
}
