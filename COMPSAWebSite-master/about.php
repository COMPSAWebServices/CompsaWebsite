<?php require_once "init.php";
$shortname = "about";
$page = Page::withShortname($shortname);
include_once "header.php";
$parsedown = new Parsedown(); ?>

<?php if ($page->getImage()) { ?>
	<img src="<?php echo $page->getImage(); ?>" role="presentation" class="about-header-image">
<?php } ?>

<h1><?php echo $page->getTitle(); ?></h1>

<?php echo $parsedown->text($page->getContent()); ?>

<?php include_once "footer.php";