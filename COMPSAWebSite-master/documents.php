<?php require_once "init.php";
$shortname = "documents";
$page = Page::withShortname($shortname);
include_once "header.php";
$parsedown = new Parsedown();
?>

<h1><?php echo $page->getTitle(); ?></h1>

<div class="policy-documents">
	<?php foreach (PolicyDocument::getAll() as $document) { ?>
		<a href="<?php echo $document->getURL(); ?>" class="document"><i class="fa fa-file"></i><?php echo $document->getName(); ?></a>
	<?php } ?>
</div>

<?php include_once "footer.php";