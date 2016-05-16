<div id="divServiceRequests" class="Section">
	<div class="Header">Service Requests</div>
	<div class="SectionBox">
		<ul>
		<?php foreach($serviceRequests as $serviceRequest) { ?>
			<li><a href="/service_request/details/<?=$serviceRequest->id?>"><?=$serviceRequest->title?></a></li>
		<?php }?>
		</ul>
	</div>
</div>