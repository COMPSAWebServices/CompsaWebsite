<?php

class PolicyDocument extends ActiveRecordAbstract {

	private $name;
	private $url;

	public function __construct($name, $url) {
		$this->setName($name);
		$this->setURL($url);
	}

	private static function withRow(array $row) {
		if (!isset($row["pid"])) {
			throw new InvalidArgumentException("Document ID missing from constructor.");
		}
		if (!isset($row["name"])) {
			throw new InvalidArgumentException("Document name missing from constructor.");
		}
		if (!isset($row["url"])) {
			throw new InvalidArgumentException("Document URL missing from constructor.");
		}
		$temp = new self($row["name"], $row["url"]);
		$temp->setPID($row["pid"]);
		return $temp;
	}

	/**
	 * @param int $id
	 *
	 * @return PolicyDocument
	 */
	public static function withID($id) {
		if (!is_int($id)) {
			if (!is_string($id) || !ctype_digit($id)) {
				throw new InvalidArgumentException("Expected int for document ID, got " . gettype($id) . "instead.");
			}
			$id = (int) $id;
		}
		try {
			$pdo  = DB::getHandle();
			$stmt = $pdo->prepare("SELECT * FROM policy WHERE pid = :pid");
			$stmt->bindParam(":pid", $id);
			$stmt->execute();
			$result = $stmt->fetch();
			if ($result === false) {
				throw new OutOfBoundsException("Nonexistent document ID supplied to constructor.");
			}
			return self::withRow($result);
		} catch (PDOException $e) {
			throw new OutOfBoundsException("Invalid document ID supplied to constructor.");
		}
	}

	/**
	 * @return PolicyDocument[]
	 */
	public static function getAll() {
		$documents = array();
		try {
			$pdo  = DB::getHandle();
			$stmt = $pdo->query("SELECT * FROM policy ORDER BY name ASC");
			$results = $stmt->fetchAll();
			if ($results === false) {
				return $documents;
			}
			foreach ($results as $row) {
				$documents[] = self::withRow($row);
			}
			return $documents;
		} catch (Exception $e) {
			throw new RuntimeException("Unable to retrieve the document list from the database.");
		}
	}

	protected function insert() {
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("INSERT INTO policy (pid, name, url) VALUES (NULL, :name, :url)");
			$stmt->bindParam(":name", $this->name);
			$stmt->bindParam(":url", $this->url);
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
			$stmt = $pdo->prepare("UPDATE policy SET name = :name, url = :url WHERE pid = :pid");
			$stmt->bindParam(":name", $this->name);
			$stmt->bindParam(":url", $this->url);
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
			$stmt = $pdo->prepare("DELETE FROM policy WHERE pid = :pid");
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
			throw new InvalidArgumentException("Invalid document name supplied as argument.");
		}
	}

	public function getURL() {
		return $this->url;
	}

	public function setURL($url) {
		if (!Validate::url($url)) {
			throw new InvalidArgumentException("Invalid URL supplied as argument.");
		}
		$this->url = $url;
	}

}