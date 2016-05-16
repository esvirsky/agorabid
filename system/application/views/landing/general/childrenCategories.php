<div id="divSubCategories" class="Section">
	<div class="Header">Sub Categories</div>
	<div class="SectionBox">
		<ul>
		<?php foreach($categories as $category) { ?>
			<li><a href="<?=$category->landingPage->getUri()?>"><?=$category->displayName?></a></li>
		<?php }?>
		</ul>
	</div>
</div>