<?php

class CouncilMember extends ActiveRecordAbstract {

	private $category;
	private $position;
	private $name;
	private $email;
	private $description;

	private static function withRow(array $row) {
		if (!isset($row["pid"])) {
			throw new InvalidArgumentException("Council member ID missing from constructor.");
		}
		if (!isset($row["category"])) {
			throw new InvalidArgumentException("Council member category missing from constructor.");
		}
		if (!isset($row["position"])) {
			throw new InvalidArgumentException("Council member position missing from constructor.");
		}
		if (!isset($row["name"])) {
			throw new InvalidArgumentException("Council member name missing from constructor.");
		}
		if (!isset($row["email"])) {
			$row["email"] = "";
		}
		if (!isset($row["description"])) {
			$row["description"] = "";
		}
		$temp = new self();
		$temp->setPID($row["pid"]);
		$temp->setCategory($row["category"]);
		$temp->setPosition($row["position"]);
		$temp->setName($row["name"]);
		$temp->setEmail($row["email"]);
		$temp->setDescription($row["description"]);
		return $temp;
	}

	/**
	 * @param int $id
	 *
	 * @return CouncilMember
	 */
	public static function withID($id) {
		if (!is_int($id)) {
			if (!is_string($id) || !ctype_digit($id)) {
				throw new InvalidArgumentException("Expected int for council member ID, got " . gettype($id) . "instead.");
			}
			$id = (int) $id;
		}
		try {
			$pdo  = DB::getHandle();
			$stmt = $pdo->prepare("SELECT * FROM council WHERE pid = :pid");
			$stmt->bindParam(":pid", $id);
			$stmt->execute();
			$result = $stmt->fetch();
			if ($result === false) {
				throw new OutOfBoundsException("Nonexistent council member ID supplied to constructor.");
			}
			return self::withRow($result);
		} catch (PDOException $e) {
			throw new OutOfBoundsException("Invalid council member ID supplied to constructor.");
		}
	}

	/**
	 * @return CouncilMember[]
	 */
	public static function getAll() {
		$members = array();
		try {
			$pdo  = DB::getHandle();
			$stmt = $pdo->query("SELECT * FROM council");
			$results = $stmt->fetchAll();
			if ($results === false) {
				return $members;
			}
			foreach ($results as $row) {
				$members[] = self::withRow($row);
			}
			return $members;
		} catch (Exception $e) {
			throw new RuntimeException("Unable to retrieve the council member list from the database.");
		}
	}

	/**
	 * @param string $category
	 *
	 * @return CouncilMember[]
	 */
	public static function getAllInCategory($category) {
		$members = array();
		try {
			$pdo  = DB::getHandle();
			$stmt = $pdo->prepare("SELECT * FROM council WHERE category = :category");
			$stmt->bindParam(":category", $category);
			$stmt->execute();
			$results = $stmt->fetchAll();
			if ($results === false) {
				return $members;
			}
			foreach ($results as $row) {
				$members[] = self::withRow($row);
			}
			return $members;
		} catch (Exception $e) {
			echo $e->getMessage();
			throw new RuntimeException("Unable to retrieve the council member list from the database.");
		}
	}

	protected function insert() {
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("INSERT INTO council (pid, category, position, name, email, description) VALUES (NULL, :category, :position, :name, :email, :description)");
			$stmt->bindParam(":category", $this->category);
			$stmt->bindParam(":position", $this->position);
			$stmt->bindParam(":name", $this->name);
			$stmt->bindParam(":email", $this->email);
			$stmt->bindParam(":description", $this->description);
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
			$stmt = $pdo->prepare("UPDATE council SET category = :category, position = :position, name = :name, email = :email, description = :description WHERE pid = :pid");
			$stmt->bindParam(":category", $this->category);
			$stmt->bindParam(":position", $this->position);
			$stmt->bindParam(":name", $this->name);
			$stmt->bindParam(":email", $this->email);
			$stmt->bindParam(":description", $this->description);
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
			$stmt = $pdo->prepare("DELETE FROM council WHERE pid = :pid");
			$stmt->bindParam(":pid", $this->pid);
			$stmt->execute();
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}

	public function getCategory() {
		return $this->category;
	}

	public function setCategory($category) {
		if (!Validate::plainText($category)) {
			throw new InvalidArgumentException("Invalid council member category supplied as argument.");
		}
		$this->category = $category;
	}

	public function getPosition() {
		return $this->position;
	}

	public function setPosition($position) {
		if (!Validate::plainText($position)) {
			throw new InvalidArgumentException("Invalid council member position supplied as argument.");
		}
		$this->position = $position;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		if (!Validate::name($name)) {
			throw new InvalidArgumentException("Invalid council member name supplied as argument.");
		}
		$this->name = $name;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		if (!$email) {
			$this->email = "";
		} else if (Validate::email($email)) {
			$this->email = $email;
		} else {
			throw new InvalidArgumentException("Invalid council member email supplied as argument.");
		}
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription($text) {
		if (!Validate::HTML($text, true)) {
			throw new InvalidArgumentException("Invalid council member description supplied as argument.");
		}
		$this->description = $text;
	}

	private static $imageMaxSize = MAX_FILESIZE;
	private static $imageDirectory = "files/council";
	private static $imageTypesList = array("SVG", "PNG", "JPG");

	/**
	 * @param string $pathPrefix
	 *
	 * @return bool|string
	 */
	public function getImage($pathPrefix = "") {
		$path = FileReadWrite::readImage($pathPrefix . self::$imageDirectory, $this->pid);
		if (defined("RELATIVE_PATH_TO_FRONTEND")) {
			return substr($path, strlen(RELATIVE_PATH_TO_FRONTEND));
		}
		return $path;
	}

	/**
	 * @param        $file
	 * @param string $pathPrefix
	 *
	 * @return bool
	 */
	public function setImage($file, $pathPrefix = "") {
		return FileReadWrite::writeImage($file, $pathPrefix . self::$imageDirectory, $this->pid, self::$imageMaxSize);
	}

}