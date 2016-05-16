		</div>
		
		<div id="divFooter">
			<div id="divFooterLinks">
				<a href="/general/tutorial">Tutorial</a> | 
				<a href="/general/contact" rel="nofollow">Contact Us</a> | 
				<a href="/general/about">About Us</a> | 
				<a href="/general/privacy" rel="nofollow">Privacy Policy</a> | 
				<a href="/general/tos" rel="nofollow">Terms of Use</a> | 
				<a href="/general/directory">Browse Site</a>
			</div>
		</div>
		<div id="divCopyright">
			Copyright 2009 Rhonex LLC
		</div>
		
		<?php if($this->config->item("live")) {?>
			<script type="text/javascript">
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
			</script>
			<script type="text/javascript">
			try {
			var pageTracker = _gat._getTracker("UA-2397479-3");
			pageTracker._trackPageview();
			} catch(err) {}</script>
		<?php } ?>
		
	</body>
	<?php 
		if(!isset($dontDisplayCarabiner) || $dontDisplayCarabiner == false)
			$this->carabiner->display();
	?>
</html>