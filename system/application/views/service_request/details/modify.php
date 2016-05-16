<div id="divServiceRequestModify">

	<?php if($serviceRequest->isOpen()) { ?>
		<button id="btnModify" type="button">Modify Request</button>
		<?php if($this->servicerequestlib->canDeleteServiceRequest($serviceRequest)) { ?>
			<button id="btnDelete" type="button">Delete Request</button>
		<?php } else { ?>
			<button id="btnEndBidding" type="button">End Bidding</button>
		<?php } ?>
	<?php } else { ?>
		<button id="btnReopen" type="button">Re-open Request</button>
	<?php } ?>
	
	
	<input id="hdnServiceRequestId" type="hidden" value="<?=$serviceRequest->id?>"/>
</div>