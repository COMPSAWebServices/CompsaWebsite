<?php require_once "init.php";
$shortname = "news";
$page = Page::withShortname($shortname);
include_once "header.php";
$parsedown = new Parsedown(); ?>

<h1><?php echo $page->getTitle(); ?></h1>

<?php foreach (NewsEntry::getAll(10) as $newsEntry) { ?>
	<div class="news-feed-entry">
		<h2><?php echo $newsEntry->getTitle(); ?></h2>
		<div class="news-feed-content">
			<?php echo $parsedown->text($newsEntry->getContent()); ?>
		</div>
	</div>
<?php } ?>
<div style="height: 1.5em;"></div>
<?php include_once "footer.php";