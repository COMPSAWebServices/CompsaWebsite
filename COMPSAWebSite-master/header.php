<?php require_once "init.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if (isset($page)) { ?>
		<title><?php echo $page->getTitle(); ?> - COMPSA</title>
	<?php } else { ?>
		<title>COMPSA - Computing Students' Association</title>
	<?php } ?>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
	<h1><a href="./" class="header-logo"><span class="text-hide">COMPSA</span><div class="header-logo"></div></a></h1>
	<nav>
		<a href="./">Home</a>
		<div class="dropdown">
			<a href="about.php" class="dropdown-label <?php if (isset($shortname) && ($shortname == "about" || $shortname == "office" || $shortname == "council")) echo 'active'; ?>">About <i class="fa fa-caret-down"></i></a>
			<nav class="dropdown-menu">
				<a href="about.php" <?php if (isset($shortname) && $shortname == "about") echo 'class="active"'; ?>><?php echo Page::withShortname("about")->getNavTitle(); ?></a>
				<a href="office.php" <?php if (isset($shortname) && $shortname == "office") echo 'class="active"'; ?>><?php echo Page::withShortname("office")->getNavTitle(); ?></a>
				<a href="council.php" <?php if (isset($shortname) && $shortname == "council") echo 'class="active"'; ?>><?php echo Page::withShortname("council")->getNavTitle(); ?></a>
			</nav>
		</div>
		<div class="dropdown">
			<a href="calendar.php" class="dropdown-label <?php if (isset($shortname) && ($shortname == "news" || $shortname == "gallery" || $shortname == "calendar")) echo 'active'; ?>">Events <i class="fa fa-caret-down"></i></a>
			<nav class="dropdown-menu">
				<a href="news.php" <?php if (isset($shortname) && $shortname == "news") echo 'class="active"'; ?>><?php echo Page::withShortname("news")->getNavTitle(); ?></a>
				<a href="gallery.php" <?php if (isset($shortname) && $shortname == "gallery") echo 'class="active"'; ?>><?php echo Page::withShortname("gallery")->getNavTitle(); ?></a>
				<a href="calendar.php" <?php if (isset($shortname) && $shortname == "calendar") echo 'class="active"'; ?>><?php echo Page::withShortname("calendar")->getNavTitle(); ?></a>
			</nav>
		</div>
		<div class="dropdown">
			<a href="links.php" class="dropdown-label <?php if (isset($shortname) && ($shortname == "links" || $shortname == "assembly" || $shortname == "documents" || $shortname == "awards")) echo 'active'; ?>">Resources <i class="fa fa-caret-down"></i></a>
			<nav class="dropdown-menu">
				<a href="links.php" <?php if (isset($shortname) && $shortname == "links") echo 'class="active"'; ?>><?php echo Page::withShortname("links")->getNavTitle(); ?></a>
				<a href="assembly.php" <?php if (isset($shortname) && $shortname == "assembly") echo 'class="active"'; ?>><?php echo Page::withShortname("assembly")->getNavTitle(); ?></a>
				<a href="documents.php" <?php if (isset($shortname) && $shortname == "documents") echo 'class="active"'; ?>><?php echo Page::withShortname("documents")->getNavTitle(); ?></a>
				<a href="awards.php" <?php if (isset($shortname) && $shortname == "awards") echo 'class="active"'; ?>><?php echo Page::withShortname("awards")->getNavTitle(); ?></a>
			</nav>
		</div>
	</nav>
	<div class="social-icons">
		<a href="<?php echo SiteSetting::withShortname("facebook_url")->getValue(); ?>" title="COMPSA on Facebook" class="social-icon-facebook"><i class="fa fa-facebook"></i></a>
		<a href="https://twitter.com/<?php echo SiteSetting::withShortname("twitter_handle")->getValue(); ?>" title="COMPSA on Twitter" class="social-icon-twitter"><i class="fa fa-twitter"></i></a>
		<a href="<?php echo SiteSetting::withShortname("instagram_url")->getValue(); ?>" title="COMPSA on Instagram" class="social-icon-instagram"><i class="fa fa-instagram"></i></a>
		<a href="<?php echo SiteSetting::withShortname("pinterest_url")->getValue(); ?>" title="COMPSA on Pinterest" class="social-icon-pinterest"><i class="fa fa-pinterest"></i></a>
	</div>
</header>

<div class="main">