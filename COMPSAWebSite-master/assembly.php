<?php require_once "init.php";
$shortname = "assembly";
$page = Page::withShortname($shortname);
include_once "header.php";
$parsedown = new Parsedown();
?>

<h1><?php echo $page->getTitle(); ?></h1>

<?php echo $parsedown->text($page->getContent()); ?>

<div class="meetings-table-container">
	<table class="meetings-table">
		<thead>
		<tr>
			<th>Date</th>
			<th>Agenda</th>
			<th>Appendices</th>
			<th>Minutes</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach (GA::getAll() as $meeting) { ?>
			<tr>
				<td><?php echo $meeting; ?></td>
				<td>
					<?php foreach ($meeting->getAgenda() as $agenda) { ?>
						<a href="<?php echo $agenda; ?>"><i class="fa fa-book"></i></a>
					<?php } ?>
				</td>
				<td>
					<?php foreach ($meeting->getAppendices() as $appendix) { ?>
						<a href="<?php echo $appendix; ?>"><i class="fa fa-bookmark"></i></a>
					<?php }
					foreach ($meeting->getBudget() as $budget) { ?>
						<a href="<?php echo $budget; ?>"> <i class="fa fa-usd"></i></a>
					<?php }
					foreach ($meeting->getLetters() as $letter) { ?>
						<a href="<?php echo $letter; ?>"> <i class="fa fa-envelope"></i></a>
					<?php } ?>
				</td>
				<td>
					<?php foreach ($meeting->getMinutes() as $minutes) { ?>
						<a href="<?php echo $minutes; ?>"><i class="fa fa-edit"></i></a>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
<div style="height: 0.75em;"></div>

<?php include_once "footer.php";