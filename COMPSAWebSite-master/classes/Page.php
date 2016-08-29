<?php

class Page extends ActiveRecordAbstract {

	private $shortname;
	private $title;
	private $navTitle;
	private $content;

	private static function withRow(array $row) {
		if (!isset($row["pid"])) {
			throw new InvalidArgumentException("Page ID missing from constructor.");
		}
		if (!isset($row["shortname"])) {
			throw new InvalidArgumentException("Page shortname missing from constructor.");
		}
		if (!isset($row["nav_title"])) {
			throw new InvalidArgumentException("Page nav title missing from constructor.");
		}
		if (!isset($row["title"])) {
			throw new InvalidArgumentException("Page title missing from constructor.");
		}
		if (!isset($row["content"])) {
			throw new InvalidArgumentException("Page content missing from constructor.");
		}
		$temp = new self();
		$temp->setPID($row["pid"]);
		$temp->setShortname($row["shortname"]);
		$temp->setTitle($row["title"]);
		$temp->setNavTitle($row["nav_title"]);
		$temp->setContent($row["content"]);
		return $temp;
	}

	/**
	 * @param int $id
	 *
	 * @return Page
	 */
	public static function withID($id) {
		if (!is_int($id)) {
			if (!is_string($id) || !ctype_digit($id)) {
				throw new InvalidArgumentException("Expected int for page ID, got " . gettype($id) . "instead.");
			}
			$id = (int) $id;
		}
		try {
			$pdo  = DB::getHandle();
			$stmt = $pdo->prepare("SELECT * FROM pages WHERE pid = :pid");
			$stmt->bindParam(":pid", $id);
			$stmt->execute();
			$result = $stmt->fetch();
			if ($result === false) {
				throw new OutOfBoundsException("Nonexistent page ID supplied to constructor.");
			}
			return self::withRow($result);
		} catch (PDOException $e) {
			throw new OutOfBoundsException("Invalid page ID supplied to constructor.");
		}
	}

	/**
	 * @param string $shortname
	 *
	 * @return Page
	 */
	public static function withShortname($shortname) {
		try {
			$pdo  = DB::getHandle();
			$stmt = $pdo->prepare("SELECT * FROM pages WHERE shortname = :shortname");
			$stmt->bindParam(":shortname", $shortname);
			$stmt->execute();
			$result = $stmt->fetch();
			if ($result === false) {
				throw new OutOfBoundsException("Nonexistent page shortname supplied to constructor.");
			}
			return self::withRow($result);
		} catch (PDOException $e) {
			throw new OutOfBoundsException("Invalid page shortname supplied to constructor.");
		}
	}

	/**
	 * @return Page[]
	 */
	public static function getAll() {
		$pages = array();
		try {
			$pdo  = DB::getHandle();
			$stmt = $pdo->query("SELECT * FROM pages");
			$results = $stmt->fetchAll();
			if ($results === false) {
				return $pages;
			}
			foreach ($results as $row) {
				$pages[] = self::withRow($row);
			}
			return $pages;
		} catch (Exception $e) {
			throw new RuntimeException("Unable to retrieve the page list from the database.");
		}
	}

	protected function insert() {
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("INSERT INTO pages (pid, shortname, title, nav_title, content) VALUES (NULL, :shortname, :title, :nav_title, :content)");
			$stmt->bindParam(":shortname", $this->shortname);
			$stmt->bindParam(":title", $this->title);
			$stmt->bindParam(":nav_title", $this->navTitle);
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
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("UPDATE pages SET shortname = :shortname, title = :title, nav_title = :nav_title, content = :content WHERE pid = :pid");
			$stmt->bindParam(":shortname", $this->shortname);
			$stmt->bindParam(":title", $this->title);
			$stmt->bindParam(":nav_title", $this->navTitle);
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
			$stmt = $pdo->prepare("DELETE FROM pages WHERE pid = :pid");
			$stmt->bindParam(":pid", $this->pid);
			$stmt->execute();
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}

	public function getShortname() {
		return $this->shortname;
	}

	public function setShortname($shortname) {
		if (!Validate::plainText($shortname)) {
			throw new InvalidArgumentException("Invalid page shortname supplied as argument.");
		}
		$this->shortname = $shortname;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		if (!Validate::plainText($title)) {
			throw new InvalidArgumentException("Invalid page title supplied as argument.");
		}
		$this->title = $title;
	}

	public function getNavTitle() {
		return $this->navTitle;
	}

	public function setNavTitle($title) {
		if (!Validate::plainText($title, true)) {
			throw new InvalidArgumentException("Invalid page nav title supplied as argument.");
		}
		$this->navTitle = $title;
	}

	public function getContent() {
		return $this->content;
	}

	public function setContent($content) {
		if (!Validate::HTML($content, true)) {
			throw new InvalidArgumentException("Invalid page content supplied as argument.");
		}
		$this->content = $content;
	}

	private static $imageMaxSize = MAX_FILESIZE;
	private static $imageDirectory = "files/pages";
	private static $imageTypesList = array("SVG", "PNG", "JPG");

	public function getImage($pathPrefix = "") {
		$path = FileReadWrite::readImage($pathPrefix . self::$imageDirectory, $this->pid);
		if (defined("RELATIVE_PATH_TO_FRONTEND")) {
			return substr($path, strlen(RELATIVE_PATH_TO_FRONTEND));
		}
		return $path;

	}

	public function setImage($file, $pathPrefix = "") {
		return FileReadWrite::writeImage($file, $pathPrefix . self::$imageDirectory, $this->pid, self::$imageMaxSize);
	}

}