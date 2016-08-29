<?php require_once "init.php";
$shortname = "awards";
$page = Page::withShortname($shortname);
include_once "header.php";
$parsedown = new Parsedown();
?>

<h1><?php echo $page->getTitle(); ?></h1>

<?php $awards = Award::getAll();
if (count($awards) > 0) { ?>
	<div class="tabs">
		<ul class="tab-list">
			<?php foreach ($awards as $award) { ?>
				<li><a href=""><?php echo $award->getName(); ?></a></li>
			<?php } ?>
		</ul>
		<div class="tab-content">
			<?php foreach ($awards as $award) { ?>
				<div class="tab-item">
					<?php echo $parsedown->text($award->getDescription()); ?>
					<h2>Past Winners</h2>
					<?php echo $parsedown->text($award->getPastWinners()); ?>
				</div>
			<?php } ?>
		</div>
	</div>
<?php } ?>
<div style="height: 1.5em;"></div>

<?php
echo $parsedown->text($page->getContent());
include_once "footer.php";