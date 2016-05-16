<div id="divSellers" class="Section">
	<div class="Header">Sellers</div>
	<div class="SectionBox">
		<ul>
		<?php foreach($sellers as $seller) { ?>
			<li><a href="/user/details/<?=$seller->username?>"><?=$seller->username?></a></li>
		<?php }?>
		</ul>
	</div>
</div>