<?php

namespace Pics\Repositories;

class PSQLPicRepository implements PicRepositoryInterface
{

	private $pdo;

	public function __construct(\PDO $pdo) {
		$this->pdo = $pdo;
	}

        public function find($params) {
		$id = base_convert($id, 36, 10);
		$stmt = $this->pdo->prepare('
			SELECT *
			FROM Picture
			WHERE :id = id');
		$stmt->bindParam(':id', $params['id']);
		$stmt->execute();
		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'Pics\Models\Picture');
		$pic = $stmt->fetch();

		if(!isset($pic->ownedby)) {
			$pic->ownedby = 'Anonymous';
		}
		
		return $pic;
	}

        public function save(Picture $pic = null) {
		$stmt = $this->pdo->prepare('
			INSERT INTO Picture (ownedby)
			VALUES (:ownedby)
			RETURNING id');
		$stmt->bindParam(':ownedby', $pic->ownedby);
		$stmt->execute();

		$id = $stmt->fetch(\PDO::FETCH_ASSOC)['id'];
		return base_convert($id, 10, 36);
	}

        public function remove(Picture $pic) {

	}

}

