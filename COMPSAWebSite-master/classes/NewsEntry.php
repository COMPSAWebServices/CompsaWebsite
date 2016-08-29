<?php

class NewsEntry extends ActiveRecordAbstract {

	private $dateUp;
	private $dateDown;
	private $title;
	private $content;

	private static function withRow(array $row) {
		if (!isset($row["pid"])) {
			throw new InvalidArgumentException("News entry ID missing from constructor.");
		}
		if (!isset($row["date_up"])) {
			throw new InvalidArgumentException("News entry date up missing from constructor.");
		}
		if (!isset($row["date_down"])) {
			throw new InvalidArgumentException("News entry date down missing from constructor.");
		}
		if (!isset($row["title"])) {
			throw new InvalidArgumentException("News entry title missing from constructor.");
		}
		if (!isset($row["content"])) {
			$row["content"] = "";
		}
		$temp = new self();
		$temp->setPID($row["pid"]);
		$temp->setDateUp($row["date_up"]);
		$temp->setDateDown($row["date_down"]);
		$temp->setTitle($row["title"]);
		$temp->setContent($row["content"]);
		return $temp;
	}

	/**
	 * @param int $id
	 *
	 * @return NewsEntry
	 */
	public static function withID($id) {
		if (!is_int($id)) {
			if (!is_string($id) || !ctype_digit($id)) {
				throw new InvalidArgumentException("Expected int for news entry ID, got " . gettype($id) . "instead.");
			}
			$id = (int) $id;
		}
		try {
			$pdo  = DB::getHandle();
			$stmt = $pdo->prepare("SELECT * FROM news WHERE pid = :pid");
			$stmt->bindParam(":pid", $id);
			$stmt->execute();
			$result = $stmt->fetch();
			if ($result === false) {
				throw new OutOfBoundsException("Nonexistent news entry ID supplied to constructor.");
			}
			return self::withRow($result);
		} catch (PDOException $e) {
			throw new OutOfBoundsException("Invalid news entry ID supplied to constructor.");
		}
	}

	/**
	 * @param int $limit
	 *
	 * @return NewsEntry[]
	 */
	public static function getAll($limit = 0) {
		$posts = array();
		try {
			$pdo  = DB::getHandle();
			$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			if (is_int($limit) || ctype_digit($limit)) {
				$limit = (int) $limit;
			} else {
				$limit = 0;
			}
			if ($limit > 0) {
				$intLimit = (int) $limit;
				$stmt = $pdo->prepare("SELECT * FROM news ORDER BY date_up DESC LIMIT :limit");
				$stmt->bindParam(":limit", $intLimit);
				$stmt->execute();
			} else {
				$stmt = $pdo->query("SELECT * FROM news ORDER BY date_up DESC, date_down ASC");
			}
			$results = $stmt->fetchAll();
			if ($results === false) {
				return $posts;
			}
			foreach ($results as $row) {
				$posts[] = self::withRow($row);
			}
			return $posts;
		} catch (Exception $e) {
			throw new RuntimeException("Unable to retrieve the news entry list from the database.");
		}
	}

	protected function insert() {
		$dateUp = $this->dateUp->format(Format::MYSQL_TIMESTAMP_FORMAT);
		$dateDown = $this->dateDown->format(Format::MYSQL_TIMESTAMP_FORMAT);
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("INSERT INTO news (pid, date_up, date_down, title, content) VALUES (NULL, :date_up, :date_down, :title, :content)");
			$stmt->bindParam(":date_up", $dateUp);
			$stmt->bindParam(":date_down", $dateDown);
			$stmt->bindParam(":title", $this->title);
			$stmt->bindParam(":content", $this->content);
			$stmt->execute();
			$this->pid = $pdo->lastInsertId();
			return $this->pid;
		} catch (PDOException $e) {
			return 0;
		}
	}

	protected function update() {
		if (!isset($this->pid) || $this->pid == 0) {
			throw new BadMethodCallException("Attempt to update nonexistent record.");
		}
		$dateUp = $this->dateUp->format(Format::MYSQL_TIMESTAMP_FORMAT);
		$dateDown = $this->dateDown->format(Format::MYSQL_TIMESTAMP_FORMAT);
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("UPDATE news SET date_up = :date_up, date_down = :date_down, title = :title, content = :content WHERE pid = :pid");
			$stmt->bindParam(":date_up", $dateUp);
			$stmt->bindParam(":date_down", $dateDown);
			$stmt->bindParam(":title", $this->title);
			$stmt->bindParam(":content", $this->content);
			$stmt->bindParam(":pid", $this->pid);
			$stmt->execute();
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}

	public function delete() {
		if (!isset($this->pid) || $this->pid == 0) {
			throw new BadMethodCallException("Attempt to delete nonexistent record.");
		}
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("DELETE FROM news WHERE pid = :pid");
			$stmt->bindParam(":pid", $this->pid);
			$stmt->execute();
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}

	/**
	 * @return DateTime
	 */
	public function getDateUp() {
		return clone $this->dateUp;
	}

	/**
	 * @param string|DateTime $date
	 */
	public function setDateUp($date) {
		if ($date instanceof DateTime) {
			$this->dateUp = clone $date;
		} else {
			try {
				$temp = DateTime::createFromFormat(Format::MYSQL_TIMESTAMP_FORMAT, $date);
				if ($temp === false) {
					throw new Exception();
				}
				$this->dateUp = $temp;
			} catch (Exception $e) {
				throw new InvalidArgumentException("Invalid date up supplied as argument.");
			}
		}
	}

	/**
	 * @return DateTime
	 */
	public function getDateDown() {
		return clone $this->dateDown;
	}

	/**
	 * @param string|DateTime $date
	 */
	public function setDateDown($date) {
		if ($date instanceof DateTime) {
			$this->dateDown = clone $date;
		} else {
			try {
				$temp = DateTime::createFromFormat(Format::MYSQL_TIMESTAMP_FORMAT, $date);
				if ($temp === false) {
					throw new Exception();
				}
				$this->dateDown = $temp;
			} catch (Exception $e) {
				throw new InvalidArgumentException("Invalid date down supplied as argument.");
			}
		}
	}

	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		if (!Validate::plainText($title)) {
			throw new InvalidArgumentException("Invalid post title supplied as argument.");
		}
		$this->title = $title;
	}

	public function getContent() {
		return $this->content;
	}

	public function setContent($content) {
		if (!Validate::HTML($content, true)) {
			throw new InvalidArgumentException("Invalid post content supplied as argument.");
		}
		$this->content = $content;
	}

}