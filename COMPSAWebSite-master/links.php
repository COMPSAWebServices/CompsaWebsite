<?php require_once "init.php";
$shortname = "links";
$page = Page::withShortname($shortname);
include_once "header.php";
$parsedown = new Parsedown();
?>

<h1><?php echo $page->getTitle(); ?></h1>

<div class="useful-links">
	<?php echo $parsedown->text($page->getContent()); ?>
</div>

<?php include_once "footer.php";