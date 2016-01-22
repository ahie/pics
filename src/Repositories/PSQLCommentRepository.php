<?php

namespace Pics\Repositories;

use Pics\Models\Comment;

class PSQLCommentRepository implements CommentRepositoryInterface
{

	private $pdo;

	public function __construct(\PDO $pdo) {
		$this->pdo = $pdo;
	}

        public function find($id) {
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
		return $comment;
	}

	public function fetchAllCommentsForPic($id) {
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
		return $comments;
	}

        public function save(Comment $comment) {
		$stmt = $this->pdo->prepare('
			INSERT INTO Comment (content, picture, parent, byuser)
			VALUES (:content, :picture, :parent, :byuser)
			RETURNING id');
		$stmt->bindParam(':content', $comment->content);
		$stmt->bindParam(':picture', $comment->picture);
		$stmt->bindParam(':parent', $comment->parent);
		$stmt->bindParam(':byuser', $comment->byuser);
		$stmt->execute();

		$id = $stmt->fetch(\PDO::FETCH_ASSOC)['id'];
		return $id;
	}

        public function remove(Comment $comment) {

	}

}

