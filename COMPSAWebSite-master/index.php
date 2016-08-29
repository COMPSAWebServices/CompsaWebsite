<?php include_once "header.php";
$parsedown = new Parsedown(); ?>

<div class="welcome-banner">
	<div class="welcome-banner-caption">
		<h1><?php echo SiteSetting::withShortname("welcome_message")->getValue(); ?></h1>
		<a href="about.php" class="button">About COMPSA</a>
	</div>
</div>

<div class="news-feed">
	<h1>Current News</h1>
	<?php foreach (NewsEntry::getAll(10) as $newsEntry) { ?>
		<div class="news-feed-entry">
			<h2><?php echo $newsEntry->getTitle(); ?></h2>
			<div class="news-feed-content">
				<?php echo $parsedown->text($newsEntry->getContent()); ?>
			</div>
		</div>
	<?php } ?>
	<a href="news.php" class="button" style="float: right; margin-bottom: 1.5em;">More</a>
</div>

</div>  <!-- /div.main -->

<div class="featured-links">
	<?php foreach (FeaturedLink::getAll() as $link) { ?>
		<a href="<?php echo $link->getURL(); ?>" class="featured-link <?php if ($link->getBackgroundType() == FeaturedLink::BACKGROUND_COVER) echo 'background-cover'; ?>" style="<?php if ($link->getBackgroundColor()) echo 'background-color: #' . $link->getBackgroundColor() . ';'; if ($link->getBackgroundType() !== FeaturedLink::BACKGROUND_SOLID) echo 'background-image: url(\'' . $link->getBackgroundImage() . '\');'; ?>">
			<span class="featured-link-preview" style="<?php if ($link->hasIcon()) echo 'background-image: url(\'' . $link->getIcon() . '\')'; ?>"></span>
			<span class="featured-link-title"><?php echo $link->getTitle(); ?></span>
		</a>
	<?php } ?>
</div>

<div>

<?php include_once "footer.php";