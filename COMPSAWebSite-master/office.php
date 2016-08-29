<?php require_once "init.php";
$shortname = "office";
$page = Page::withShortname($shortname);
include_once "header.php";
$parsedown = new Parsedown(); ?>

<h1><?php echo $page->getTitle(); ?></h1>

<?php echo $parsedown->text($page->getContent()); ?>

<?php include_once "footer.php";