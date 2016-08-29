<?php require_once "init.php";
$shortname = "gallery";
$page = Page::withShortname($shortname);
$parsedown = new Parsedown();

if (isset($_GET["g"])) {
	try {
		$gallery = Gallery::withID($_GET["g"]);
	} catch (Exception $e) {
		header("Location: gallery.php");
	}
	$count = count($gallery);
	if ($count == 0) {
		$countText = "No photos";
	} else if ($count == 1) {
		$countText = "1 photo";
	} else {
		$countText = $count . " photos";
	}
	include_once "header.php"; ?>

	<a href="gallery.php" class="button" style="float: right; margin-left: 1.5em;">Back</a>
	<h1 class="gallery-title">Gallery: <?php echo $gallery->getName(); ?></h1>
	<h3 class="gallery-meta"><span class="date"><?php echo Format::date($gallery->getDate(), "F jS, Y"); ?></span> <span class="photo-count"><i class="fa fa-photo"></i> <?php echo $countText; ?></span></h3>
	<div class="gallery-single">
	</div>

	<div style="height: 1.5em;"></div>

<?php } else {
	include_once "header.php"; ?>

	<h1><?php echo $page->getTitle(); ?></h1>

	<?php echo $parsedown->text($page->getContent()); ?>

	<div class="gallery-list">
		<?php
		$galleries = Gallery::getAll();
		$numGalleries = count($galleries);
		if ($numGalleries > 3) {
			$extraCells = (3 - (count($galleries) % 3)) % 3;
		} else {
			$extraCells = 0;
		}

		foreach ($galleries as $gallery) { ?>
			<a href="gallery.php?g=<?php echo $gallery->getPID(); ?>" class="gallery-thumbnail">
		<span class="gallery-meta">
			<span class="date"><?php echo Format::date($gallery->getDate(), "F jS, Y"); ?></span>
			<span class="name"><?php echo $gallery->getName(); ?></span>
		</span>
				<span class="photo-count"><i class="fa fa-photo"></i> <?php echo count($gallery); ?></span>
			</a>
		<?php }
		for ($i = 0; $i < $extraCells; $i++) { ?>
			<div class="gallery-thumbnail-filler"></div>
		<?php } ?>
	</div>
	<div style="height: 1.5em;"></div>

<?php }

include_once "footer.php";