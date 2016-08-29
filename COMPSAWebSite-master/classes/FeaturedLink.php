<?php

class FeaturedLink extends ActiveRecordAbstract {

	const BACKGROUND_SOLID = 0;
	const BACKGROUND_TILE = 1;
	const BACKGROUND_COVER = 2;

	private $title;
	private $url;
	private $backgroundColor;
	private $backgroundType;
	private $icon;

	public function __construct($title, $url, $backgroundColor, $icon) {
		$this->setTitle($title);
		$this->setURL($url);
		$this->setBackgroundColor($backgroundColor);
		$this->backgroundType = self::BACKGROUND_SOLID;
		$this->icon = ($icon) ? true : false;
	}

	private static function withRow(array $row) {
		if (!isset($row["pid"])) {
			throw new InvalidArgumentException("Featured link ID missing from constructor.");
		}
		if (!isset($row["title"])) {
			throw new InvalidArgumentException("Featured link title missing from constructor.");
		}
		if (!isset($row["url"])) {
			throw new InvalidArgumentException("Featured link URL missing from constructor.");
		}
		if (!isset($row["background_color"])) {
			$row["background_color"] = "";
		}
		if (!isset($row["background_image"])) {
			$row["background_image"] = self::BACKGROUND_SOLID;
		}
		if (!isset($row["icon"])) {
			$row["icon"] = 0;
		}
		$temp = new self($row["title"], $row["url"], $row["background_color"], $row["icon"]);
		$temp->setPID($row["pid"]);
		$temp->backgroundType = (int) $row["background_type"];
		return $temp;
	}

	/**
	 * @param int $id
	 *
	 * @return FeaturedLink
	 */
	public static function withID($id) {
		if (!is_int($id)) {
			if (!is_string($id) || !ctype_digit($id)) {
				throw new InvalidArgumentException("Expected int for featured link ID, got " . gettype($id) . "instead.");
			}
			$id = (int) $id;
		}
		try {
			$pdo  = DB::getHandle();
			$stmt = $pdo->prepare("SELECT * FROM featured_links WHERE pid = :pid");
			$stmt->bindParam(":pid", $id);
			$stmt->execute();
			$result = $stmt->fetch();
			if ($result === false) {
				throw new OutOfBoundsException("Nonexistent featured link ID supplied to constructor.");
			}
			return self::withRow($result);
		} catch (PDOException $e) {
			throw new OutOfBoundsException("Invalid featured link ID supplied to constructor.");
		}
	}

	/**
	 * @return FeaturedLink[]
	 */
	public static function getAll() {
		$links = array();
		try {
			$pdo  = DB::getHandle();
			$stmt = $pdo->query("SELECT * FROM featured_links ORDER BY title ASC");
			$results = $stmt->fetchAll();
			if ($results === false) {
				return $links;
			}
			foreach ($results as $row) {
				$links[] = self::withRow($row);
			}
			return $links;
		} catch (Exception $e) {
			var_dump($e);
			throw new RuntimeException("Unable to retrieve the featured link list from the database.");
		}
	}

	protected function insert() {
		$hasIcon = ($this->icon) ? 1 : 0;
		try {
			$pdo = DB::getHandle();
			$stmt = $pdo->prepare("INSERT INTO featured_links (pid, title, url, background_color, background_type, icon) VALUES (NULL, :title, :url, :background_color, :background_type, :icon)");
			$stmt->bindParam(":title", $this->title);
			$stmt->bindParam(":url", $this->url);
			$stmt->bindParam(":background_color", $this->backgroundColor);
			$stmt->bindParam(":background_type", $this->backgroundType);
			$stmt->bindParam(":icon", $hasIcon);
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
			$stmt = $pdo->prepare("UPDATE featured_links SET title = :title, url = :url, background_color = :background_color, background_type = :background_type, icon = :icon WHERE pid = :pid");
			$stmt->bindParam(":title", $this->title);
			$stmt->bindParam(":url", $this->url);
			$stmt->bindParam(":background_color", $this->backgroundColor);
			$stmt->bindParam(":background_type", $this->backgroundType);
			$stmt->bindParam(":icon", $hasIcon);
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
			$stmt = $pdo->prepare("DELETE FROM featured_links WHERE pid = :pid");
			$stmt->bindParam(":pid", $this->pid);
			$stmt->execute();
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}

	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		if (!Validate::plainText($title)) {
			throw new InvalidArgumentException("Invalid link title supplied as argument.");
		}
		$this->title = $title;
	}

	public function getURL() {
		return $this->url;
	}

	public function setURL($url) {
		if (!Validate::url($url)) {
			throw new InvalidArgumentException("Invalid link URL supplied as argument.");
		}
		$this->url = $url;
	}

	public function getBackgroundColor() {
		return $this->backgroundColor;
	}

	public function setBackgroundColor($hex) {
		if (!$hex) {
			$this->backgroundColor = "";
		} else {
			if ($hex[0] == "#") {
				$hex = substr($hex, 1);
			}
			if (preg_match("/^([a-f0-9])\\1\\1|([a-f0-9]){6}$/i", $hex)) {
				$this->backgroundColor = $hex;
			} else {
				throw new InvalidArgumentException("Invalid hex color code supplied as argument.");
			}
		}
	}

	public function getBackgroundType() {
		return $this->backgroundType;
	}

	private static $backgroundImageMaxSize = MAX_FILESIZE;
	private static $backgroundImageDirectory = "files/featured_links/backgrounds";
	private static $backgroundImageTypesList = array("SVG", "PNG", "JPG");

	/**
	 * @param string $pathPrefix
	 *
	 * @return bool|string
	 */
	public function getBackgroundImage($pathPrefix = "") {
		$path = FileReadWrite::readImage($pathPrefix . self::$backgroundImageDirectory, $this->pid);
		if (defined("RELATIVE_PATH_TO_FRONTEND")) {
			return substr($path, strlen(RELATIVE_PATH_TO_FRONTEND));
		}
		return $path;
	}

	/**
	 * @param        $file
	 * @param string $pathPrefix
	 * @param int    $backgroundType
	 *
	 * @return bool
	 */
	public function setBackgroundImage($file, $pathPrefix = "", $backgroundType = self::BACKGROUND_TILE) {
		if ($backgroundType == self::BACKGROUND_TILE || $backgroundType == self::BACKGROUND_COVER) {
			$this->backgroundType = $backgroundType;
			return FileReadWrite::writeImage($file, $pathPrefix . self::$backgroundImageDirectory, $this->pid, self::$backgroundImageMaxSize);
		} else {
			throw new InvalidArgumentException("Invalid link background type supplied as argument.");
		}
	}

	public function hasIcon() {
		return $this->icon;
	}

	private static $iconMaxSize = MAX_FILESIZE;
	private static $iconDirectory = "files/featured_links/icons";
	private static $iconTypesList = array("SVG", "PNG", "JPG");

	/**
	 * @param string $pathPrefix
	 *
	 * @return bool|string
	 */
	public function getIcon($pathPrefix = "") {
		$path = FileReadWrite::readImage($pathPrefix . self::$iconDirectory, $this->pid);
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
	public function setIcon($file, $pathPrefix = "") {
		return FileReadWrite::writeImage($file, $pathPrefix . self::$iconDirectory, $this->pid, self::$iconMaxSize);
	}

}