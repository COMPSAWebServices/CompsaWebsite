<?php

class Gallery extends ActiveRecordAbstract implements Countable, IteratorAggregate {

	private $name;
	private $date;
	private $photos = array();

	public function __construct($name, $date) {
		$this->setName($name);
		$this->setDate($date);
	}

	private static function withRow(array $row) {
		if (!isset($row["pid"])) {
			throw new InvalidArgumentException("Gallery ID missing from constructor.");
		}
		if (!isset($row["name"])) {
			throw new InvalidArgumentException("Gallery name missing from constructor.");
		}
		if (!isset($row["date"])) {
			throw new InvalidArgumentException("Gallery date missing from constructor.");
		}
		$temp = new self($row["name"], $row["date"]);
		$temp->setPID($row["pid"]);
		return $temp;
	}

	/**
	 * @param int $id
	 *
	 * @return Gallery
	 */
	public static function withID($id) {
		if (!is_int($id)) {
			if (!is_string($id) || !ctype_digit($id)) {
				throw new InvalidArgumentException("Expected int for gallery ID, got " . gettype($id) . "instead.");
			}
			$id = (int) $id;
		}
		try {
			$pdo  = DB::getHandle();
			$stmt = $pdo->prepare("SELECT * FROM galleries WHERE pid = :pid");
			$stmt->bindParam(":pid", $id);
			$stmt->execute();
			$result = $stmt->fetch();
			if ($result === false) {
				throw new OutOfBoundsException("Nonexistent gallery ID supplied to constructor.");
			}
			return self::withRow($result);
		} catch (PDOException $e) {
			throw new OutOfBoundsException("Invalid gallery ID supplied to constructor.");
		}
	}

	/**
	 * @return Gallery[]
	 */
	public static function getAll() {
		$galleries = array();
		try {
			$pdo  = DB::getHandle();
			$stmt = $pdo->query("SELECT * FROM galleries");
			$results = $stmt->fetchAll();
			if ($results === false) {
				return $galleries;
			}
			foreach ($results as $row) {
				$galleries[] = self::withRow($row);
			}
			return $galleries;
		} catch (Exception $e) {
			throw new RuntimeException("Unable to retrieve the gallery list from the database.");
		}
	}

	protected function insert() {
		$date = $this->date->format(Format::MYSQL_DATE_FORMAT);
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("INSERT INTO galleries (pid, name, date) VALUES (NULL, :name, :date)");
			$stmt->bindParam(":name", $this->name);
			$stmt->bindParam(":date", $date);
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
		$date = $this->date->format(Format::MYSQL_DATE_FORMAT);
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("UPDATE galleries SET name = :name, date = :date WHERE pid = :pid");
			$stmt->bindParam(":name", $this->name);
			$stmt->bindParam(":date", $date);
			$stmt->bindParam(":pid", $this->pid);
			$stmt->execute();
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}

	function delete() {
		if (!isset($this->pid) || $this->pid == 0) {
			throw new BadMethodCallException("Attempt to delete nonexistent record.");
		}
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("DELETE FROM galleries WHERE pid = :pid");
			$stmt->bindParam(":pid", $this->pid);
			$stmt->execute();
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		if (!Validate::plainText($name)) {
			throw new InvalidArgumentException("Invalid gallery name supplied as argument.");
		}
		$this->name = $name;
	}

	public function getDate() {
		return clone $this->date;
	}

	/**
	 * @param DateTime|string $date
	 */
	public function setDate($date) {
		if (Validate::date($date)) {
			if ($date instanceof DateTime) {
				$this->date = clone $date;
			} else {
				$this->date = DateTime::createFromFormat(Format::MYSQL_DATE_FORMAT, $date);
			}
			$this->date->setTime(0, 0, 0);
		} else {
			throw new InvalidArgumentException("Invalid gallery date supplied as argument.");
		}
	}

	public function getPhotos() {}

	public function addPhoto() {}

	public function deletePhoto() {}

	/**
	 * Count elements of an object
	 * @link  http://php.net/manual/en/countable.count.php
	 * @return int The custom count as an integer.
	 * </p>
	 * <p>
	 * The return value is cast to an integer.
	 * @since 5.1.0
	 */
	public function count() {
		return count($this->photos);
	}

	/**
	 * Retrieve an external iterator
	 * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return Traversable An instance of an object implementing <b>Iterator</b> or
	 * <b>Traversable</b>
	 * @since 5.0.0
	 */
	public function getIterator() {
		return new ArrayIterator($this->photos);
	}
}