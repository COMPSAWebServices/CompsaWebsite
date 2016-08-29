<?php

class Event extends ActiveRecordAbstract {

	private $allDayEvent;
	private $startTimestamp;
	private $endTimestamp;
	private $name;
	private $location;
	private $description;

	public static function withRow(array $row) {
		if (!isset($row["pid"])) {
			throw new InvalidArgumentException("Event ID missing from constructor.");
		}
		if (!isset($row["all_day_event"])) {
			$row["all_day_event"] = 0;
		}
		if (!isset($row["start_timestamp"])) {
			throw new InvalidArgumentException("Starting timestamp missing from constructor.");
		}
		if (!isset($row["end_timestamp"])) {
			throw new InvalidArgumentException("Ending timestamp missing from constructor.");
		}
		if (!isset($row["name"])) {
			throw new InvalidArgumentException("Event name title missing from constructor.");
		}
		if (!isset($row["location"])) {
			$row["location"] = "";
		}
		if (!isset($row["description"])) {
			$row["description"] = "";
		}
		$temp = new self();
		$temp->setPID($row["pid"]);
		$temp->setAllDayStatus($row["all_day_event"]);
		$temp->setStartTimestamp($row["start_timestamp"]);
		$temp->setEndTimestamp($row["end_timestamp"]);
		$temp->setName($row["name"]);
		$temp->setLocation($row["location"]);
		$temp->setDescription($row["description"]);
		return $temp;
	}

	/**
	 * @param int $id
	 *
	 * @return Event
	 */
	public static function withID($id) {
		if (!is_int($id)) {
			if (!is_string($id) || !ctype_digit($id)) {
				throw new InvalidArgumentException("Expected int for event ID, got " . gettype($id) . "instead.");
			}
			$id = (int) $id;
		}
		try {
			$pdo  = DB::getHandle();
			$stmt = $pdo->prepare("SELECT * FROM events WHERE pid = :pid");
			$stmt->bindParam(":pid", $id);
			$stmt->execute();
			$result = $stmt->fetch();
			if ($result === false) {
				throw new OutOfBoundsException("Nonexistent event ID supplied to constructor.");
			}
			return self::withRow($result);
		} catch (PDOException $e) {
			throw new OutOfBoundsException("Invalid event ID supplied to constructor.");
		}
	}

	/**
	 * @param bool $includePast
	 *
	 * @return Event[]
	 */
	public static function getAll($includePast = false) {
		$events = array();
		try {
			$pdo  = DB::getHandle();
			if ($includePast) {
				$stmt = $pdo->query("SELECT * FROM events ORDER BY start_timestamp ASC, end_timestamp ASC");
			} else {
				$stmt = $pdo->query("SELECT * FROM events WHERE end_timestamp >= NOW() ORDER BY start_timestamp ASC, end_timestamp ASC");
			}
			$results = $stmt->fetchAll();
			if ($results === false) {
				return $events;
			}
			foreach ($results as $row) {
				$events[] = self::withRow($row);
			}
			return $events;
		} catch (Exception $e) {
			throw new RuntimeException("Unable to retrieve the event list from the database.");
		}
	}

	protected function insert() {
		$startTimestamp = $this->startTimestamp->format(Format::MYSQL_TIMESTAMP_FORMAT);
		$endTimestamp = $this->endTimestamp->format(Format::MYSQL_TIMESTAMP_FORMAT);
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("INSERT INTO events (pid, all_day_event, start_timestamp, end_timestamp, name, location, description) VALUES (NULL, :all_day_event, :start_timestamp, :end_timestamp, :name, :location, :description)");
			$stmt->bindParam(":all_day_event", $this->allDayEvent);
			$stmt->bindParam(":start_timestamp", $startTimestamp);
			$stmt->bindParam(":end_timestamp", $endTimestamp);
			$stmt->bindParam(":name", $this->name);
			$stmt->bindParam(":location", $this->location);
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
		$startTimestamp = $this->startTimestamp->format(Format::MYSQL_TIMESTAMP_FORMAT);
		$endTimestamp = $this->endTimestamp->format(Format::MYSQL_TIMESTAMP_FORMAT);
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("UPDATE events SET all_day_event = :all_day_event, start_timestamp = :start_timestamp, end_timestamp = :end_timestamp, name = :name, location = :location, description = :description WHERE pid = :pid");
			$stmt->bindParam(":all_day_event", $this->allDayEvent);
			$stmt->bindParam(":start_timestamp", $startTimestamp);
			$stmt->bindParam(":end_timestamp", $endTimestamp);
			$stmt->bindParam(":name", $this->name);
			$stmt->bindParam(":location", $this->location);
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
			$stmt = $pdo->prepare("DELETE FROM events WHERE pid = :pid");
			$stmt->bindParam(":pid", $this->pid);
			$stmt->execute();
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}

	public function isAllDayEvent() {
		return $this->getAllDayStatus();
	}

	public function getAllDayStatus() {
		return ($this->allDayEvent) ? true : false;
	}

	public function setAllDayStatus($isAllDayEvent) {
		$this->allDayEvent = ($isAllDayEvent) ? 1 : 0;
	}

	/**
	 * @return DateTime
	 */
	public function getStartTimestamp() {
		return clone $this->startTimestamp;
	}

	/**
	 * @param string|DateTime $timestamp
	 */
	public function setStartTimestamp($timestamp) {
		if ($timestamp instanceof DateTime) {
			$this->startTimestamp = clone $timestamp;
		} else {
			try {
				$temp = DateTime::createFromFormat(Format::MYSQL_TIMESTAMP_FORMAT, $timestamp);
				if ($temp === false) {
					throw new Exception();
				}
				$this->startTimestamp = $temp;
			} catch (Exception $e) {
				throw new InvalidArgumentException("Invalid starting timestamp supplied as argument.");
			}
		}
	}

	/**
	 * @return DateTime
	 */
	public function getEndTimestamp() {
		return clone $this->endTimestamp;
	}

	/**
	 * @param string|DateTime $timestamp
	 */
	public function setEndTimestamp($timestamp) {
		if ($timestamp instanceof DateTime) {
			$this->endTimestamp = clone $timestamp;
		} else {
			try {
				$temp = DateTime::createFromFormat(Format::MYSQL_TIMESTAMP_FORMAT, $timestamp);
				if ($temp === false) {
					throw new Exception();
				}
				$this->endTimestamp = $temp;
			} catch (Exception $e) {
				throw new InvalidArgumentException("Invalid ending timestamp supplied as argument.");
			}
		}
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		if (!Validate::plainText($name)) {
			throw new InvalidArgumentException("Invalid event name supplied as argument.");
		}
		$this->name = $name;
	}

	public function getLocation() {
		return $this->location;
	}

	public function setLocation($location) {
		if (!Validate::plainText($location, true)) {
			throw new InvalidArgumentException("Invalid event location supplied as argument.");
		}
		$this->location = $location;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription($description) {
		if (!Validate::plainText($description, true)) {
			throw new InvalidArgumentException("Invalid event description supplied as argument.");
		}
		$this->description = $description;
	}

}