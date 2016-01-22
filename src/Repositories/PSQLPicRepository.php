<?php

namespace Pics\Repositories;

use Pics\Models\Picture;

class PSQLPicRepository implements PicRepositoryInterface
{

	private $pdo;

	public function __construct(\PDO $pdo) {
		$this->pdo = $pdo;
	}

        public function find($id) {
		$stmt = $this->pdo->prepare('
			SELECT 	id, uploaded, url,
				filesize,
				COALESCE(ownedby, \'Anonymous\') AS ownedby
			FROM Picture
			WHERE :id = id');
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'Pics\Models\Picture');
		$pic = $stmt->fetch();
		return $pic;
	}

        public function save(Picture $pic) {
		$stmt = $this->pdo->prepare('
			INSERT INTO Picture (url, filesize, ownedby)
			VALUES (:url, :filesize, :ownedby)
			RETURNING id');
		$stmt->bindParam(':url', $pic->url);
		$stmt->bindParam(':filesize', $pic->filesize);
		$stmt->bindParam(':ownedby', $pic->ownedby);
		$stmt->execute();

		$id = $stmt->fetch(\PDO::FETCH_ASSOC)['id'];
		return $id;
	}

        public function remove(Picture $pic) {

	}

}

