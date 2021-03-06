<?php

namespace Pics\Repositories;

use Pics\Models\Comment;

class PSQLCommentRepository implements CommentRepositoryInterface
{

	private $pdo;
	private $m;

	public function __construct(\PDO $pdo, \Memcached $m) {
		$this->pdo = $pdo;
		$this->m = $m;
	}

        public function find($id) {
		$comment = $this->m->get('comment' . $id);
		if(!!$comment) {
			return $comment;
		}

		$stmt = $this->pdo->prepare('
			SELECT	id, content,
				picture, parent,
				COALESCE(byuser, \'Anonymous\') AS byuser,
				posted, edited
			FROM Comment
			WHERE :id = id');
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'Pics\Models\Comment');
		$comment = $stmt->fetch();

		$this->m->set('comment' . $id, $comment, 60);
		return $comment;
	}

	public function fetchAllCommentsForPic($id) {
		$comments = $this->m->get('cfp' . $id);
		if(!!$comments) {
			return $comments;
		}

		$stmt = $this->pdo->prepare('
			SELECT	id, content,
				picture, parent,
				COALESCE(byuser, \'Anonymous\') AS byuser,
				posted, edited
			FROM Comment
			WHERE picture = :id');
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'Pics\Models\Comment');
		$comments = $stmt->fetchAll();

		$comments = $this->createCommentTree($comments);
		$this->m->set('cfp' . $id, $comments, 60);

		return $comments;
	}

        public function save(Comment $comment) {
		$stmt = $this->pdo->prepare('
			INSERT INTO Comment (content, picture, parent, byuser)
			VALUES (:content, :picture, :parent, :byuser)
			RETURNING id');

		if(!mb_detect_encoding($comment->content, 'UTF-8', true)) {
			$comment->content = utf8_encode($comment->content);
		}

		$stmt->bindParam(':content', $comment->content);
		$stmt->bindParam(':picture', $comment->picture);
		$stmt->bindParam(':parent', $comment->parent);
		$stmt->bindParam(':byuser', $comment->byuser);
		$stmt->execute();

		// Remove cached comments for this picture
		// since they are no longer valid
		$this->m->delete('cfp' . $comment->picture);

		$id = $stmt->fetch(\PDO::FETCH_ASSOC)['id'];
		return $id;
	}

        public function remove(Comment $comment) {

	}

	private function createCommentTree(array &$comments, $parent = null) {
		$branch = array();
		foreach($comments as &$comment) {
			if($comment->parent === $parent) {
				$children = $this->createCommentTree($comments, $comment->id);
				if($children) {
					$comment->children = $children;
				}
				$branch[] = $comment;
				unset($comment);
			}
		}
		return $branch;
	}
}

