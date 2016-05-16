<?php 

$this->servicerequestlib->model->order_by("created", "DESC");
$serviceRequests = $this->servicerequestlib->getServiceRequestsByUser($this->userlib->getLoggedInUser());

?>

<?php if(!empty($serviceRequests)) { ?>
	<div id="divServiceRequestsToggle" class="Foldout"><a name="service_requests"><img src="/images/collapsed.gif" /> <span>My Service Requests</span></a></div>
	<div id="divServiceRequests" class="SectionBox">
		<table cellspacing="0" cellpadding="0" class="InfoTable">
			<tr><th>Date</th><th>Service Request</th><th width="75">Status</th><th width="40">&nbsp;</th></tr>
			<?php foreach($serviceRequests as $key => $serviceRequest) { ?>
								
				<tr class="<?=$key%2 == 1 ? "tr1" : "tr0"?>">
					<td class="Date"><?=formatDateForView(strtotime($serviceRequest->created))?></td>
					<td><?=$serviceRequest->title?></td>
					<td><?=$serviceRequest->status?></td>
					<td><a href="/service_request/details/<?=$serviceRequest->id?>">View</a></td>
				</tr>
				
			<?php } ?>
		</table>
	</div>
<?php } ?>