<?php 

$this->bidlib->model->order_by("created", "DESC");
$bids = $this->bidlib->getBidsByUser($this->userlib->getLoggedInUser(), true);

?>

<?php if(!empty($bids)) { ?>
	<div id="divBidsToggle" class="Foldout"><a name="bids"><img src="/images/collapsed.gif" /> <span>My Bids</span></a></div>
	<div id="divBids" class="SectionBox">
		<table cellspacing="0" cellpadding="0" class="InfoTable">
			<tr><th>Date</th><th>Service Request</th><th width="85">Bid Status</th><th width="80" title="Service Request Status">SR Status</th><th width="40">&nbsp;</th></tr>
			<?php foreach($bids as $key => $bid) { ?>
				<tr class="<?=$key%2 == 1 ? "tr1" : "tr0"?>">
					<td class="Date"><?=formatDateForView(strtotime($bid->created))?></td>
					<td><a href="/service_request/details/<?=$bid->serviceRequest->id?>"><?=$bid->serviceRequest->title?></a></td>
					<td><?=$bid->accepted ? "<span class='AcceptedStatus'>accepted</span>" : "none"?></td>
					<td><?=$bid->serviceRequest->status?></td>
					<td><a href="/service_request/details/<?=$bid->serviceRequest->id?>#bid<?=$bid->id?>">View</a></td>
				</tr>
				
			<?php } ?>
		</table>
	</div>
<?php } ?>