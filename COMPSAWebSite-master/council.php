<?php require_once "init.php";
$shortname = "council";
$page = Page::withShortname($shortname);
include_once "header.php";
$parsedown = new Parsedown(); ?>

<h1><?php echo $page->getTitle(); ?></h1>

<?php echo $parsedown->text($page->getContent()); ?>

	<div class="tabs council-list">
		<ul class="tab-list">
			<li><a href="">Executive</a></li>
			<li><a href="">Commissioners</a></li>
			<li><a href="">Representatives</a></li>
			<li><a href="">Non-Council Members</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-item">
				<?php foreach (CouncilMember::getAllInCategory("executive") as $member) { ?>
					<div class="council-member">
						<?php if ($member->getImage()) { ?>
							<div class="council-member-pic" style="background-image: url('<?php echo $member->getImage(); ?>');"></div>
						<?php } else { ?>
							<div class="council-member-pic" style="background-image: url('https://www.drupal.org/files/profile_default.png');"></div>
						<?php } ?>
						<h3><?php echo $member->getPosition(); ?></h3>
						<h4>
							<?php echo $member->getName(); ?>
							<?php if ($member->getEmail()) { ?>
								<a href="mailto:<?php echo $member->getEmail(); ?>" title="Email <?php echo $member->getEmail(); ?>"><i class="fa fa-envelope"></i></a>
							<?php } ?>
						</h4>
						<div class="council-member-description">
							<?php echo $parsedown->text($member->getDescription()); ?>
						</div>
					</div>
				<?php } ?>
			</div>
			<div class="tab-item">
				<?php foreach (CouncilMember::getAllInCategory("commissioner") as $member) { ?>
					<div class="council-member">
						<?php if ($member->getImage()) { ?>
							<div class="council-member-pic" style="background-image: url('<?php echo $member->getImage(); ?>');"></div>
						<?php } else { ?>
							<div class="council-member-pic" style="background-image: url('https://www.drupal.org/files/profile_default.png');"></div>
						<?php } ?>
						<h3><?php echo $member->getPosition(); ?></h3>
						<h4>
							<?php echo $member->getName(); ?>
							<?php if ($member->getEmail()) { ?>
								<a href="mailto:<?php echo $member->getEmail(); ?>" title="Email <?php echo $member->getEmail(); ?>"><i class="fa fa-envelope"></i></a>
							<?php } ?>
						</h4>
						<div class="council-member-description">
							<?php echo $parsedown->text($member->getDescription()); ?>
						</div>
					</div>
				<?php } ?>
			</div>
			<div class="tab-item">
				<?php foreach (CouncilMember::getAllInCategory("representative") as $member) { ?>
					<div class="council-member">
						<?php if ($member->getImage()) { ?>
							<div class="council-member-pic" style="background-image: url('<?php echo $member->getImage(); ?>');"></div>
						<?php } else { ?>
							<div class="council-member-pic" style="background-image: url('https://www.drupal.org/files/profile_default.png');"></div>
						<?php } ?>
						<h3><?php echo $member->getPosition(); ?></h3>
						<h4>
							<?php echo $member->getName(); ?>
							<?php if ($member->getEmail()) { ?>
								<a href="mailto:<?php echo $member->getEmail(); ?>" title="Email <?php echo $member->getEmail(); ?>"><i class="fa fa-envelope"></i></a>
							<?php } ?>
						</h4>
						<div class="council-member-description">
							<?php echo $parsedown->text($member->getDescription()); ?>
						</div>
					</div>
				<?php } ?>
			</div>
			<div class="tab-item">
				<?php foreach (CouncilMember::getAllInCategory("non_council") as $member) { ?>
					<div class="council-member">
						<?php if ($member->getImage()) { ?>
							<div class="council-member-pic" style="background-image: url('<?php echo $member->getImage(); ?>');"></div>
						<?php } else { ?>
							<div class="council-member-pic" style="background-image: url('https://www.drupal.org/files/profile_default.png');"></div>
						<?php } ?>
						<h3><?php echo $member->getPosition(); ?></h3>
						<h4>
							<?php echo $member->getName(); ?>
							<?php if ($member->getEmail()) { ?>
								<a href="mailto:<?php echo $member->getEmail(); ?>" title="Email <?php echo $member->getEmail(); ?>"><i class="fa fa-envelope"></i></a>
							<?php } ?>
						</h4>
						<div class="council-member-description">
							<?php echo $parsedown->text($member->getDescription()); ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>

<?php include_once "footer.php";