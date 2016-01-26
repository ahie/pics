<?php

namespace Pics\Repositories;

use Pics\Models\Picture;

class PSQLPicRepository implements PicRepositoryInterface
{

	private $pdo;
	private $m;

	public function __construct(\PDO $pdo,\Memcached $m) {
		$this->pdo = $pdo;
		$this->m = $m;
	}

        public function find($id) {

		$pic = $this->m->get('pic' . $id);
		if(!!$pic) {
			return $pic;
		}
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

		$this->m->set('pic' . $id, $pic, 60);
		return $pic;
	}

	public function fetchNewestPics() {

		$pics = $this->m->get('newestPics');
		if(!!$pics) {
			return $pics;
		}

		$stmt = $this->pdo->prepare('
			SELECT 	id, uploaded, url,
				filesize,
				COALESCE(ownedby, \'Anonymous\') AS ownedby
			FROM Picture
			ORDER BY uploaded DESC
			LIMIT 32');
		$stmt->execute();
		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'Pics\Models\Picture');
		$pics = $stmt->fetchAll();

		$this->m->set('newestPics', $pics, 60);
		return $pics;
	}

	public function fetchBefore($pid, $num) {

		$pics = $this->m->get('b.' . $pid . '.' . $num);
		if(!!$pics) {
			return $pics;
		}

		$stmt = $this->pdo->prepare('
                        SELECT  id, uploaded, url,
                                filesize,
                                COALESCE(ownedby, \'Anonymous\') AS ownedby
                        FROM Picture
			WHERE uploaded < (SELECT uploaded FROM Picture WHERE :id = id)
                        ORDER BY uploaded DESC
                        LIMIT :num');
		$stmt->bindParam(':id', $pid);
		$stmt->bindParam(':num', $num);
                $stmt->execute();
                $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Pics\Models\Picture');
                $pics = $stmt->fetchAll();

		$this->m->set('b.' . $pid . '.' . $num, $pics, 60);
		return $pics;
	}

        public function save(Picture $pic) {
		try {
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
		catch (\PDOException $e) {
			return null;
		}
	}

        public function remove(Picture $pic) {
		$stmt = $this->pdo->prepare('DELETE FROM Picture WHERE :id = id');
		$stmt->bindParam(':id', $pic->id);
		$stmt->execute();
	}

}

