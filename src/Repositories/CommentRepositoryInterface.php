<?php

namespace Pics\Repositories;

use Pics\Models\Comment;

interface CommentRepositoryInterface
{
	public function find($id);
	public function fetchAllCommentsForPic($id);
	public function save(Comment $comment);
	public function remove(Comment $comment);
}

