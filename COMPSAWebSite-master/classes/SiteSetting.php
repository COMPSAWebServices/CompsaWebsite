<?php

class SiteSetting extends ActiveRecordAbstract {

	private $type;
	private $shortname;
	private $value;

	const TYPE_INT = 1;
	const TYPE_STRING = 2;
	const TYPE_BOOLEAN = 3;

	private static function withRow(array $row) {
		if (!isset($row["pid"])) {
			throw new InvalidArgumentException("Setting ID missing from constructor.");
		}
		if (!isset($row["setting_type"])) {
			throw new InvalidArgumentException("Setting type missing from constructor.");
		}
		if (!isset($row["setting_name"])) {
			throw new InvalidArgumentException("Setting shortname missing from constructor.");
		}
		if (!isset($row["setting_value"])) {
			throw new InvalidArgumentException("Setting value missing from constructor.");
		}
		$temp = new self();
		$temp->setPID($row["pid"]);
		$temp->setType($row["setting_type"]);
		$temp->setShortname($row["setting_name"]);
		$temp->setValue($row["setting_value"]);
		return $temp;

	}

	/**
	 * @param int $id
	 *
	 * @return SiteSetting
	 */
	public static function withID($id) {
		if (!is_int($id)) {
			if (!is_string($id) || !ctype_digit($id)) {
				throw new InvalidArgumentException("Expected int for setting ID, got " . gettype($id) . "instead.");
			}
			$id = (int) $id;
		}
		try {
			$pdo  = DB::getHandle();
			$stmt = $pdo->prepare("SELECT * FROM settings WHERE pid = :pid");
			$stmt->bindParam(":pid", $id);
			$stmt->execute();
			$result = $stmt->fetch();
			if ($result === false) {
				throw new OutOfBoundsException("Nonexistent setting ID supplied to constructor.");
			}
			return self::withRow($result);
		} catch (PDOException $e) {
			throw new OutOfBoundsException("Invalid setting ID supplied to constructor.");
		}
	}

	/**
	 * @param string $shortname
	 *
	 * @return SiteSetting
	 */
	public static function withShortname($shortname) {
		try {
			$pdo  = DB::getHandle();
			$stmt = $pdo->prepare("SELECT * FROM settings WHERE setting_name = :setting_name");
			$stmt->bindParam(":setting_name", $shortname);
			$stmt->execute();
			$result = $stmt->fetch();
			if ($result === false) {
				throw new OutOfBoundsException("Nonexistent setting shortname supplied to constructor.");
			}
			return self::withRow($result);
		} catch (PDOException $e) {
			throw new OutOfBoundsException("Invalid setting shortname supplied to constructor.");
		}
	}

	/**
	 * @return SiteSetting[]
	 */
	public static function getAll() {
		$settings = array();
		try {
			$pdo  = DB::getHandle();
			$stmt = $pdo->query("SELECT * FROM settings");
			$results = $stmt->fetchAll();
			if ($results === false) {
				return $settings;
			}
			foreach ($results as $row) {
				$settings[] = self::withRow($row);
			}
			return $settings;
		} catch (Exception $e) {
			throw new RuntimeException("Unable to retrieve the settings list from the database.");
		}
	}

	protected function insert() {
		if ($this->type == self::TYPE_BOOLEAN) {
			$value = ($this->value) ? "true" : "false";
		} else {
			$value = $this->value;
		}
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("INSERT INTO settings (pid, setting_type, setting_name, setting_value) VALUES (NULL, :setting_type, :setting_name, :setting_value)");
			$stmt->bindParam(":setting_type", $this->type);
			$stmt->bindParam(":setting_name", $this->shortname);
			$stmt->bindParam(":setting_value", $value);
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
		if ($this->type == self::TYPE_BOOLEAN) {
			$value = ($this->value) ? "true" : "false";
		} else {
			$value = $this->value;
		}
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("UPDATE settings SET setting_type = :setting_type, setting_name = :setting_name, setting_value = :setting_value WHERE pid = :pid");
			$stmt->bindParam(":setting_type", $this->type);
			$stmt->bindParam(":setting_name", $this->shortname);
			$stmt->bindParam(":setting_value", $value);
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
			$stmt = $pdo->prepare("DELETE FROM settings WHERE pid = :pid");
			$stmt->bindParam(":pid", $this->pid);
			$stmt->execute();
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}

	/**
	 * @return int
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param int $type
	 */
	public function setType($type) {
		if (is_int($type) || ctype_digit($type)) {
			$this->type = (int) $type;
		} else {
			throw new InvalidArgumentException("Expected int for setting type, got " . gettype($type) . "instead.");
		}
	}

	/**
	 * @return string
	 */
	public function getShortname() {
		return $this->shortname;
	}

	/**
	 * @param string $shortname
	 */
	public function setShortname($shortname) {
		if (Validate::plainText($shortname)) {
			$this->shortname = $shortname;
		} else {
			throw new InvalidArgumentException("Invalid setting shortname supplied as argument.");
		}
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value) {
		if ($this->type == self::TYPE_INT) {
			if (!$value) {
				$this->value = 0;
			} else if (Validate::int($value)) {
				$this->value = (int) $value;
			} else {
				throw new InvalidArgumentException("Invalid int supplied as setting value.");
			}
		} else if ($this->type == self::TYPE_STRING) {
			if (!$value) {
				$this->value = "";
			} else if (Validate::plainText($value)) {
				$this->value = $value;
			} else {
				throw new InvalidArgumentException("Invalid string supplied as setting value.");
			}
		} else if ($this->type == self::TYPE_BOOLEAN) {
			$value = strtolower(trim($value));
			if ($value == "true") {
				$this->value = true;
			} else if ($value == "false") {
				$this->value = false;
			} else {
				$this->value = ($value) ? true : false;
			}
		}
	}

}