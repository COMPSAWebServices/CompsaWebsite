<?php require_once "init.php";

$shortname = "calendar";
$page = Page::withShortname($shortname);

// set the display year, if not passed as a URL parameter
if (!isset($_GET["y"])) {
	$year = date("Y");
} else {
	$year = $_GET["y"];
}
if (!is_int($year) && ctype_digit($year)) {
	$year = (int) $year;
}

// set the display month, if not passed as a URL parameter
// (1 = January, 2 = February, ... 12 = December)
if (!isset($_GET["m"])) {
	$month = date("n");
} else {
	$month = $_GET["m"];
}
if (!is_int($month) && ctype_digit($month)) {
	$month = (int) $month;
}

$parsedown = new Parsedown();

if (isset($_GET["id"]) && ctype_digit($_GET["id"])) {
	if (date("Y") == $year && date("n") == $month) {
		$backButtonLink = 'calendar.php';
	} else {
		$backButtonLink = 'calendar.php?m=' . $month . '&y=' . $year;
	}
	try {
		$event = Event::withID($_GET["id"]);
	} catch (Exception $e) {
		header("Location: " . $backButtonLink);
	}
	include_once "header.php"; ?>
	<a href="<?php echo $backButtonLink ?>" class="button" style="float: right; margin-left: 1.5em;">Back</a>
	<h1>Event: <?php echo $event->getName(); ?></h1>
	<table class="event-details">
		<?php if ($event->getStartTimestamp() == $event->getEndTimestamp() ||
		          $event->isAllDayEvent() && ($event->getStartTimestamp()->setTime(0, 0, 0) == $event->getEndTimestamp()->setTime(0, 0, 0))) {
			if ($event->isAllDayEvent()) { ?>
				<tr>
					<th><i class="fa fa-calendar"></i> Date</th>
					<td><?php echo Format::date($event->getStartTimestamp(), "F j, Y"); ?></td>
				</tr>
			<?php } else { ?>
				<tr>
					<th><i class="fa fa-calendar"></i> Date/Time</th>
					<td><?php echo Format::date($event->getStartTimestamp(), "F j, Y \\a\\t g:i A"); ?></td>
				</tr>
			<?php }
		} else {
			if ($event->isAllDayEvent()) { ?>
				<tr>
					<th><i class="fa fa-calendar"></i> Start</th>
					<td><?php echo Format::date($event->getStartTimestamp(), "F j, Y"); ?></td>
				</tr>
				<tr>
					<th><i class="fa fa-calendar"></i> End</th>
					<td><?php echo Format::date($event->getEndTimestamp(), "F j, Y"); ?></td>
				</tr>
			<?php } else { ?>
				<tr>
					<th><i class="fa fa-calendar"></i> Start</th>
					<td><?php echo Format::date($event->getStartTimestamp(), "F j, Y \\a\\t g:i A"); ?></td>
				</tr>
				<tr>
					<th><i class="fa fa-calendar"></i> End</th>
					<td><?php echo Format::date($event->getEndTimestamp(), "F j, Y \\a\\t g:i A"); ?></td>
				</tr>
			<?php }
		} ?>
		<?php if ($event->getLocation()) { ?>
			<tr>
				<th><i class="fa fa-map-marker"></i> Location</th>
				<td><?php echo $event->getLocation(); ?></td>
			</tr>
		<?php } ?>
		<?php if ($event->getDescription()) { ?>
			<tr>
				<th><i class="fa fa-info-circle"></i> Event Description</th>
				<td><?php echo $parsedown->text($event->getDescription()); ?></td>
			</tr>
		<?php } ?>
	</table>
<?php } else {
	$calendar = EventCalendar::getCalendarForMonth($month, $year);
	include_once "header.php"; ?>
	<div class="calendar-nav">
		<?php echo $calendar->outputNavigation(); ?>
	</div>
	<h2 class="calendar-month-title"><?php echo $calendar->getDisplayMonth(); ?></h2>
	<div class="calendar-container">
		<?php echo $calendar->outputCalendar(); ?>
	</div>
	<div style="height: 1.5em;"></div>
<?php } ?>

<?php include_once "footer.php";