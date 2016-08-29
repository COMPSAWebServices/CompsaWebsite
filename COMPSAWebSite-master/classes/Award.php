<?php

class Award extends ActiveRecordAbstract {

	private $name;
	private $description;
	private $pastWinners;

	public function __construct($name, $description, $pastWinners) {
		$this->setName($name);
		$this->setDescription($description);
		$this->setPastWinners($pastWinners);
	}

	private static function withRow(array $row) {
		if (!isset($row["pid"])) {
			throw new InvalidArgumentException("Award ID missing from constructor.");
		}
		if (!isset($row["name"])) {
			throw new InvalidArgumentException("Award name missing from constructor.");
		}
		if (!isset($row["description"])) {
			throw new InvalidArgumentException("Award description missing from constructor.");
		}
		if (!isset($row["past_winners"])) {
			throw new InvalidArgumentException("Award past winners missing from constructor.");
		}
		$temp = new self($row["name"], $row["description"], $row["past_winners"]);
		$temp->setPID($row["pid"]);
		return $temp;
	}

	/**
	 * @param int $id
	 *
	 * @return Award
	 */
	public static function withID($id) {
		if (!is_int($id)) {
			if (!is_string($id) || !ctype_digit($id)) {
				throw new InvalidArgumentException("Expected int for award ID, got " . gettype($id) . "instead.");
			}
			$id = (int) $id;
		}
		try {
			$pdo  = DB::getHandle();
			$stmt = $pdo->prepare("SELECT * FROM awards WHERE pid = :pid");
			$stmt->bindParam(":pid", $id);
			$stmt->execute();
			$result = $stmt->fetch();
			if ($result === false) {
				throw new OutOfBoundsException("Nonexistent award ID supplied to constructor.");
			}
			return self::withRow($result);
		} catch (PDOException $e) {
			throw new OutOfBoundsException("Invalid award ID supplied to constructor.");
		}
	}

	/**
	 * @return Award[]
	 */
	public static function getAll() {
		$awards = array();
		try {
			$pdo  = DB::getHandle();
			$stmt = $pdo->query("SELECT * FROM awards ORDER BY name ASC");
			$results = $stmt->fetchAll();
			if ($results === false) {
				return $awards;
			}
			foreach ($results as $row) {
				$awards[] = self::withRow($row);
			}
			return $awards;
		} catch (Exception $e) {
			throw new RuntimeException("Unable to retrieve the awards list from the database.");
		}
	}

	protected function insert() {
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("INSERT INTO awards (pid, name, description, past_winners) VALUES (NULL, :name, :description, :past_winners)");
			$stmt->bindParam(":name", $this->name);
			$stmt->bindParam(":description", $this->description);
			$stmt->bindParam(":past_winners", $this->pastWinners);
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
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("UPDATE awards SET name = :name, description = :description, past_winners = :past_winners WHERE pid = :pid");
			$stmt->bindParam(":name", $this->name);
			$stmt->bindParam(":description", $this->description);
			$stmt->bindParam(":past_winners", $this->pastWinners);
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
			$stmt = $pdo->prepare("DELETE FROM awards WHERE pid = :pid");
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
		if (Validate::plainText($name)) {
			$this->name = $name;
		} else {
			throw new InvalidArgumentException("Invalid award name supplied as argument.");
		}
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription($text) {
		if (!Validate::HTML($text)) {
			throw new InvalidArgumentException("Invalid award description supplied as argument");
		}
		$this->description = $text;
	}

	public function getPastWinners() {
		return $this->pastWinners;
	}

	public function setPastWinners($text) {
		if (!Validate::HTML($text)) {
			throw new InvalidArgumentException("Invalid award past winners supplied as argument");
		}
		$this->pastWinners = $text;
	}

}