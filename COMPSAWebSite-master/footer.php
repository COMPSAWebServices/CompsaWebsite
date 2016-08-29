</div>      <!-- /div.main -->

<footer>
	<div class="footer-sections">
		<div>
			<h2>Links</h2>
			<a href="about.php"><?php echo Page::withShortname("about")->getTitle(); ?></a>
			<a href="assembly.php"><?php echo Page::withShortname("assembly")->getTitle(); ?></a>
			<a href="http://www.cs.queensu.ca">School of Computing</a>
		</div>
		<div class="social-icons">
			<h2>Connect</h2>
			<a href="<?php echo SiteSetting::withShortname("facebook_url")->getValue(); ?>" title="COMPSA on Facebook" class="social-icon-facebook"><i class="fa fa-facebook"></i> Facebook</a>
			<a href="https://twitter.com/<?php echo SiteSetting::withShortname("twitter_handle")->getValue(); ?>" title="COMPSA on Twitter" class="social-icon-twitter"><i class="fa fa-twitter"></i> Twitter</a>
			<a href="<?php echo SiteSetting::withShortname("instagram_url")->getValue(); ?>" title="COMPSA on Instagram" class="social-icon-instagram"><i class="fa fa-instagram"></i> Instagram</a>
			<a href="<?php echo SiteSetting::withShortname("pinterest_url")->getValue(); ?>" title="COMPSA on Pinterest" class="social-icon-pinterest"><i class="fa fa-pinterest"></i> Pinterest</a>
		</div>
	</div>
	<a href="http://compsawebservices.com">Powered by COMPSA Web Services</a>
	<a href="./" class="footer-logo"></a>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="js/tabs.js"></script>

</body>
</html>