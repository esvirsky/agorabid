<?php 

$this->bidlib->model->order_by("created", "DESC");
$bids = $this->bidlib->getUnreviewedBidsByUser($this->userlib->getLoggedInUser());

?>

<?php if(!empty($bids)) { ?>
	<div id="divPendingReviewsToggle" class="Foldout"><a name="pending_reviews"><img src="/images/collapsed.gif" /> <span>Pending Reviews</span></a>&nbsp;&nbsp;<span class="Notification">( <?=count($bids)?> pending )</span></div>
	<div id="divPendingReviews" class="SectionBox">
		<table cellspacing="0" cellpadding="0" class="InfoTable">
			<tr><th width="150">User</th><th>Service Request</th><th width="110">&nbsp;</th></tr>
			<?php foreach($bids as $key => $bid) { ?>
				
				<tr class="<?=$key%2 == 1 ? "tr1" : "tr0"?>">
					<td><a href="/user/details/<?=$bid->user->username?>"><?=$bid->user->username?></a></td>
					<td><a href="/service_request/details/<?=$bid->serviceRequest->id?>#bid<?=$bid->id?>"><?=$bid->serviceRequest->title?></a></td>
					<td><a href="/user/create_bid_review/<?=$bid->id?>">Leave feedback</a></td>
				</tr>
				
			<?php } ?>
		</table>
	</div>
<?php } ?>