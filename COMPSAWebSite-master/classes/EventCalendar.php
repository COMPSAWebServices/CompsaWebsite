<?php

class EventCalendar {

	private static $months = array(
		1 => "January",
		2 => "February",
		3 => "March",
		4 => "April",
		5 => "May",
		6 => "June",
		7 => "July",
		8 => "August",
		9 => "September",
		10 => "October",
		11 => "November",
		12 => "December",
	);

	public $events = array();
	private $month;
	private $year;
	private $monthObj;

	public function __construct(DateTime $month) {
		$this->month = (int) $month->format("m");
		$this->year = (int) $month->format("Y");

		$this->monthObj = new DateTime();
		$this->monthObj->setDate($this->year, $this->month, 1);
		$this->monthObj->setTime(0, 0, 0);
	}

	public static function getCalendarForMonth($month, $year) {
		$firstDayOfMonth = new DateTime();
		$firstDayOfMonth->setDate($year, $month, 1);
		$firstDayOfMonth->setTime(0, 0, 0);
		$firstDayOfMonth = $firstDayOfMonth->format(Format::MYSQL_TIMESTAMP_FORMAT);

		$lastDayOfMonth = new DateTime();
		$lastDayOfMonth->setDate($year, $month, cal_days_in_month(CAL_GREGORIAN, $month, $year));
		$lastDayOfMonth->setTime(23, 59, 59);
		$lastDayOfMonth = $lastDayOfMonth->format(Format::MYSQL_TIMESTAMP_FORMAT);

		$monthObj = new DateTime();
		$monthObj->setDate($year, $month, 1);
		$monthObj->setTime(0, 0, 0);

		$calendar = new self($monthObj);

		// query any events that should be displayed this month
		$pdo  = DB::getHandle();
		$stmt = $pdo->prepare("SELECT * FROM events WHERE start_timestamp <= :lastday AND end_timestamp >= :firstday ORDER BY start_timestamp ASC");
		$stmt->bindParam(":lastday", $lastDayOfMonth);
		$stmt->bindParam(":firstday", $firstDayOfMonth);
		$stmt->execute();
		$results = $stmt->fetchAll();
		if ($results === false) {
			return $calendar;
		}
		foreach ($results as $event) {
			$calendar->addEvent(Event::withRow($event));
		}
		return $calendar;
	}

	public function addEvent(Event $event) {
		$startDay = (int) $event->getStartTimestamp()->format("d");
		$endDay = (int) $event->getEndTimestamp()->format("d");
		for ($i = $startDay; $i <= $endDay; $i++) {
			if (!isset($this->events[$i])) {
				$this->events[$i] = array();
			}
			array_push($this->events[$i], $event);
		}
	}

	public function getDisplayMonth() {
		return $this->monthObj->format("F Y");
	}

	public function outputNavigation() {

		$todayMonth = date("n");
		$todayYear = date("Y");

		if ($this->month == 1) {
			$prevMonth = 12;
			$nextMonth = 2;
			$prevMonthYear = $this->year - 1;
			$nextMonthYear = $this->year;
		} else if ($this->month == 12) {
			$prevMonth = 11;
			$nextMonth = 1;
			$prevMonthYear = $this->year;
			$nextMonthYear = $this->year + 1;
		} else {
			$prevMonth = $this->month - 1;
			$nextMonth = $this->month + 1;
			$prevMonthYear = $this->year;
			$nextMonthYear = $this->year;
		}

		if ($this->month == 1) {
			$prevMonthTitle = self::$months[$prevMonth] . " " . $prevMonthYear;
		} else {
			$prevMonthTitle = self::$months[$prevMonth];
		}
		if ($this->month == 12) {
			$nextMonthTitle = self::$months[$nextMonth] . " " . $nextMonthYear;
		} else {
			$nextMonthTitle = self::$months[$nextMonth];
		}

		$string = '<ul class="calendar_nav_list">' . PHP_EOL;

		// previous month button
		$string .= '<li class="calendar_nav_listItem">' . PHP_EOL;
		if ($prevMonth == (int) $todayMonth && $prevMonthYear == (int) $todayYear) {
			$string .= '<a href="calendar.php" class="button">' . $prevMonthTitle . '</a>' . PHP_EOL;
		} else {
			$string .= '<a href="calendar.php?m=' . $prevMonth . '&y=' . $prevMonthYear . '" class="button">' . $prevMonthTitle . '</a>' . PHP_EOL;
		}
		$string .= '</li>' . PHP_EOL;

		// today button
		$string .= '<li class="calendar_nav_listItem calendar_nav_today"><a href="calendar.php" class="button">Today</a></li>' . PHP_EOL;

		// next month button
		$string .= '<li class="calendar_nav_listItem">' . PHP_EOL;
		if ($nextMonth == (int) $todayMonth && $nextMonthYear == (int) $todayYear) {
			$string .= '<a href="calendar.php" class="button">' . $nextMonthTitle . '</a>' . PHP_EOL;
		} else {
			$string .= '<a href="calendar.php?m=' . $nextMonth . '&y=' . $nextMonthYear . '" class="button">' . $nextMonthTitle . '</a>' . PHP_EOL;
		}
		$string .= '</li>' . PHP_EOL;

		$string .= '</ul>' . PHP_EOL;

		return $string;
	}

	public function outputCalendar() {
		$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
		$firstDayWeekday = ((int) $this->monthObj->format("w"));  // 0 = Sunday, 1 = Monday, ... 6 = Saturday

		// build a 7-by-n array to hold the calendar cells
		// - all the logic happens here, and the output code below
		//   just loops through the array generating table rows

		$calendarCells = array();

		// empty cell reference
		$emptyCellContent = "";

		// pad the first week with empty cells if required
		$daysBeforeMonth = $firstDayWeekday;
		while ($daysBeforeMonth > 0) {
			array_push($calendarCells, $emptyCellContent);
			$daysBeforeMonth--;
		}

		// see if this is the current month/year, for highlighting today's date
		$now = new DateTime();
		$now->setTime(0, 0, 0);
		$isThisMonth = ($this->monthObj->format("F Y") == $now->format("F Y"));
		$today = (int) $now->format("d");

		// create a cell for each day of the month
		for ($i = 0; $i < $daysInMonth; $i++) {
			$day = $i + 1;
			$isToday = ($isThisMonth && $day == $today);

			// day label
			if ($isToday) {
				$cellContents = '<p class="calendar_date today">' . $day . '</p>' . PHP_EOL;
			} else {
				$cellContents = '<p class="calendar_date">' . $day . '</p>' . PHP_EOL;
			}

			// event list
			$cellContents .= '<div class="calendar_events">' . PHP_EOL;

			if (isset($this->events[$day])) {
				$cellContents .= '<ul>' . PHP_EOL;
				foreach ($this->events[$day] as $event) {
					$cellContents .= '<li><a href="calendar.php?m=' . $this->month . '&y=' . $this->year . '&id=' . $event->getPID() . '" class="event_label">' . $event->getName() . '</a></li>' . PHP_EOL;
				}
				$cellContents .= '</ul>' . PHP_EOL;
			}

			$cellContents .= '</div>' . PHP_EOL;

			array_push($calendarCells, $cellContents);
		}

		// pad the last week with empty cells if required
		$daysAfterMonth = 7 - count($calendarCells) % 7;
		if ($daysAfterMonth < 7) {
			while ($daysAfterMonth > 0) {
				array_push($calendarCells, $emptyCellContent);
				$daysAfterMonth--;
			}
		}

		// start building the table
		$string = '<table class="calendar_table">' . PHP_EOL;

		// generate the first row for weekday headings
		$string .= '<tr>' . PHP_EOL;
		$string .= '<th class="calendar_cell">Sunday</th>' . PHP_EOL;
		$string .= '<th class="calendar_cell">Monday</th>' . PHP_EOL;
		$string .= '<th class="calendar_cell">Tuesday</th>' . PHP_EOL;
		$string .= '<th class="calendar_cell">Wednesday</th>' . PHP_EOL;
		$string .= '<th class="calendar_cell">Thursday</th>' . PHP_EOL;
		$string .= '<th class="calendar_cell">Friday</th>' . PHP_EOL;
		$string .= '<th class="calendar_cell">Saturday</th>' . PHP_EOL;
		$string .= '</tr>' . PHP_EOL;

		// output the previously-generated calendar cell array as table rows
		for ($i = 0; $i < count($calendarCells); $i++) {
			$mod = $i % 7;
			if ($mod == 0) {
				$string .= '<tr class="calendar_row">' . PHP_EOL;
			}
			$string .= '<td class="calendar_cell">' . $calendarCells[$i] . '</td>' . PHP_EOL;
			if ($mod == 6) {
				$string .= '</tr>' . PHP_EOL;
			}
		}

		$string .= '</table>' . PHP_EOL;

		return $string;
	}

}