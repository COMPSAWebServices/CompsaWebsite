<?php

class GA extends ActiveRecordAbstract {

	/**
	 * @var DateTime $date
	 */
	private $date;
	private $agenda = array();
	private $appendices = array();
	private $budget = array();
	private $letters = array();
	private $minutes = array();

	public function __construct($date) {
		$this->setDate($date);
	}

	/**
	 * @param array $row
	 *
	 * @return GA
	 */
	public static function withRow(array $row) {
		if (!isset($row["pid"])) {
			throw new InvalidArgumentException("Primary ID missing from constructor.");
		}
		if (!isset($row["date"])) {
			throw new InvalidArgumentException("GA date missing from constructor.");
		}
		if (!isset($row["agenda"]) || empty($row["agenda"])) {
			$row["agenda"] = "[]";
		}
		if (!isset($row["appendices"]) || empty($row["appendices"])) {
			$row["appendices"] = "[]";
		}
		if (!isset($row["budget"]) || empty($row["budget"])) {
			$row["budget"] = "[]";
		}
		if (!isset($row["letters"]) || empty($row["letters"])) {
			$row["letters"] = "[]";
		}
		if (!isset($row["minutes"]) || empty($row["minutes"])) {
			$row["minutes"] = "[]";
		}
		$temp = new self($row["date"]);
		$temp->setPID($row["pid"]);
		$temp->setAgendaWithJSON($row["agenda"]);
		$temp->setAppendicesWithJSON($row["appendices"]);
		$temp->setBudgetWithJSON($row["budget"]);
		$temp->setLettersWithJSON($row["letters"]);
		$temp->setMinutesWithJSON($row["minutes"]);
		return $temp;
	}

	/**
	 * @param int $id
	 *
	 * @return GA
	 */
	public static function withID($id) {
		if (!is_int($id)) {
			if (is_string($id) && ctype_digit($id)) {
				$id = (int) $id;
			} else {
				throw new InvalidArgumentException("Expected int for primary ID, got " . gettype($id) . "instead.");
			}
		}
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("SELECT * FROM GA WHERE pid = :pid");
			$stmt->bindParam(":pid", $id);
			$stmt->execute();
			$result = $stmt->fetch();
			if ($result === false) {
				throw new OutOfBoundsException("Nonexistent primary ID supplied to constructor.");
			}
			return self::withRow($result);
		} catch (PDOException $e) {
			throw new OutOfBoundsException("Invalid primary ID supplied to constructor.");
		}
	}

	/**
	 * @return GA[]
	 */
	public static function getAll() {
		$meetings = array();
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->query("SELECT * FROM GA ORDER BY date DESC");
			$results = $stmt->fetchAll();
			if ($results === false) {
				throw new RuntimeException("Unable to retrieve the GA list.");
			}
			foreach ($results as $meeting) {
				$meetings[] = self::withRow($meeting);
			}
			return $meetings;
		} catch (PDOException $e) {
			throw new RuntimeException("Unable to retrieve the GA list.");
		}
	}

	/**
	 * @return int
	 */
	protected function insert() {
		$date = Format::date($this->date, Format::MYSQL_DATE_FORMAT);
		$agendaJSON = $this->getAgendaJSON();
		$appendicesJSON = $this->getAppendicesJSON();
		$budgetJSON = $this->getBudgetJSON();
		$lettersJSON = $this->getLettersJSON();
		$minutesJSON = $this->getMinutesJSON();
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("INSERT INTO GA (pid, date, agenda, appendices, budget, letters, minutes) VALUES (NULL, :date, :agenda, :appendices, :budget, :letters, :minutes)");
			$stmt->bindParam(":date", $date);
			$stmt->bindParam(":agenda", $agendaJSON);
			$stmt->bindParam(":appendices", $appendicesJSON);
			$stmt->bindParam(":budget", $budgetJSON);
			$stmt->bindParam(":letters", $lettersJSON);
			$stmt->bindParam(":minutes", $minutesJSON);
			$stmt->execute();
			$this->setPID($pdo->lastInsertId());
			return $this->pid;
		} catch (PDOException $e) {
			return 0;
		}

	}

	/**
	 * @return bool
	 */
	protected function update() {
		if (!isset($this->pid) || $this->pid == 0) {
			throw new BadMethodCallException("Attempt to update nonexistent record.");
		}
		$date = Format::date($this->date, Format::MYSQL_DATE_FORMAT);
		$agendaJSON = $this->getAgendaJSON();
		$appendicesJSON = $this->getAppendicesJSON();
		$budgetJSON = $this->getBudgetJSON();
		$lettersJSON = $this->getLettersJSON();
		$minutesJSON = $this->getMinutesJSON();
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("UPDATE GA SET date = :date, agenda = :agenda, appendices = :appendices, budget = :budget, letters = :letters, minutes = :minutes WHERE pid = :pid");
			$stmt->bindParam(":date", $date);
			$stmt->bindParam(":agenda", $agendaJSON);
			$stmt->bindParam(":appendices", $appendicesJSON);
			$stmt->bindParam(":budget", $budgetJSON);
			$stmt->bindParam(":letters", $lettersJSON);
			$stmt->bindParam(":minutes", $minutesJSON);
			$stmt->bindParam(":pid", $this->pid);
			$stmt->execute();
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}

	/**
	 * @return bool
	 */
	function delete() {
		if (!isset($this->pid) || $this->pid == 0) {
			throw new BadMethodCallException("Attempt to delete nonexistent record.");
		}
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("DELETE FROM GA WHERE pid = :pid");
			$stmt->bindParam(":pid", $this->pid);
			$stmt->execute();
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->date->format("F jS, Y");
	}

	/**
	 * @return DateTime
	 */
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
			throw new InvalidArgumentException("Invalid GA date supplied as argument.");
		}
	}

	/**
	 * @return string[]
	 */
	public function getAgenda() {
		return $this->agenda;
	}

	/**
	 * @return string
	 */
	public function getAgendaJSON() {
		return json_encode($this->agenda, JSON_UNESCAPED_SLASHES);
	}

	/**
	 * @param string[] $agenda
	 */
	public function setAgendaWithArray(array $agenda) {
		$this->agenda = $agenda;
	}

	/**
	 * @param string $agenda
	 */
	public function setAgendaWithJSON($agenda) {
		$this->setAgendaWithArray(json_decode($agenda));
	}

	/**
	 * @param string $agenda
	 */
	public function addAgenda($agenda) {
		array_push($this->agenda, $agenda);
	}

	/**
	 * @return string[]
	 */
	public function getAppendices() {
		return $this->appendices;
	}

	/**
	 * @return string
	 */
	public function getAppendicesJSON() {
		return json_encode($this->appendices, JSON_UNESCAPED_SLASHES);
	}

	/**
	 * @param string[] $appendices
	 */
	public function setAppendicesWithArray(array $appendices) {
		$this->appendices = $appendices;
	}

	/**
	 * @param string $appendices
	 */
	public function setAppendicesWithJSON($appendices) {
		$this->setAppendicesWithArray(json_decode($appendices));
	}

	/**
	 * @param string $appendix
	 */
	public function addAppendix($appendix) {
		array_push($this->appendices, $appendix);
	}

	/**
	 * @return string[]
	 */
	public function getBudget() {
		return $this->budget;
	}

	/**
	 * @return string
	 */
	public function getBudgetJSON() {
		return json_encode($this->budget, JSON_UNESCAPED_SLASHES);
	}

	/**
	 * @param string[] $budget
	 */
	public function setBudgetWithArray(array $budget) {
		$this->budget = $budget;
	}

	/**
	 * @param string $budget
	 */
	public function setBudgetWithJSON($budget) {
		$this->setBudgetWithArray(json_decode($budget));
	}

	/**
	 * @param string $budget
	 */
	public function addBudget($budget) {
		array_push($this->budget, $budget);
	}

	/**
	 * @return string[]
	 */
	public function getLetters() {
		return $this->letters;
	}

	/**
	 * @return string
	 */
	public function getLettersJSON() {
		return json_encode($this->letters, JSON_UNESCAPED_SLASHES);
	}

	/**
	 * @param string[] $letters
	 */
	public function setLettersWithArray(array $letters) {
		$this->letters = $letters;
	}

	/**
	 * @param string $letters
	 */
	public function setLettersWithJSON($letters) {
		$this->setLettersWithArray(json_decode($letters));
	}

	/**
	 * @param string $letter
	 */
	public function addLetter($letter) {
		array_push($this->letters, $letter);
	}

	/**
	 * @return string[]
	 */
	public function getMinutes() {
		return $this->minutes;
	}

	/**
	 * @return string
	 */
	public function getMinutesJSON() {
		return json_encode($this->minutes, JSON_UNESCAPED_SLASHES);
	}

	/**
	 * @param string[] $minutes
	 */
	public function setMinutesWithArray(array $minutes) {
		$this->minutes = $minutes;
	}

	/**
	 * @param string $minutes
	 */
	public function setMinutesWithJSON($minutes) {
		$this->setMinutesWithArray(json_decode($minutes));
	}

	/**
	 * @param string $minutes
	 */
	public function addMinutes($minutes) {
		array_push($this->minutes, $minutes);
	}
}