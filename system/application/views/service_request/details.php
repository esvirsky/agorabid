<?php 

	$title = $serviceRequest->title . " (" . $serviceRequest->category->name . ")";
	$description = "Service request " . $serviceRequest->title . " - " . $serviceRequest->category->name;
	headerView($title, $description);
?>

<?php

$user = $this->userlib->getLoggedInUser();
$userBid = $user == null ? null : $this->bidlib->getBidByUserServiceRequest($user, $serviceRequest, true);

$isLoggedIn = $user != null;
$isCreator = $isLoggedIn && $serviceRequest->userId == $user->id;
$isBidder = $userBid != null;
$isAcceptedBidder = $userBid != null && $userBid->accepted;
$showEverything = $isCreator || $isAcceptedBidder;
$showExactAddress = $isCreator || ($isAcceptedBidder && $serviceRequest->atLocation);

$latitude = $showExactAddress ? $serviceRequest->location->latitude : $serviceRequest->location->latitude + $serviceRequest->location->offsetLatitude;
$longitude = $showExactAddress ? $serviceRequest->location->longitude : $serviceRequest->location->longitude + $serviceRequest->location->offsetLongitude;

$bids = array();
if($isCreator)
{
	$this->bidlib->model->order_by("created", "ASC");
	$bids = $this->bidlib->getBidsByServiceRequest($serviceRequest, true);
}
else if($isBidder)
	$bids[] = $userBid;

$messageThreads = array();
if($isCreator)
	$messageThreads = $serviceRequest->messageThreads;
else if($isLoggedIn && !$isBidder)
{
	$messageThread = $this->messagelib->getMessageThreadByUserServiceRequest($user, $serviceRequest);
	if($messageThread != null)
		$messageThreads[] = $messageThread;
}

$showCreateMessage = $isLoggedIn && !$isCreator && $user->isSeller() && empty($messageThreads) && !$isBidder && $serviceRequest->isOpen();
$showCreateBid = !$isBidder && !$isCreator && $isLoggedIn && $user->isSeller() && $serviceRequest->isOpen();
$showServiceRequestModify = $isCreator;

?>

<?php if(!$isCreator) { ?>	<style> #divSRDetails #divBids, #divSRDetails #divMessages { display: block; } </style> <?php } ?>

<div id="divSRDetails">

		<?=$this->load->view('service_request/details/info', array("serviceRequest" => $serviceRequest, "showEverything" => $showEverything, "showExactAddress" => $showExactAddress, "bids" => $bids))?>
		
		<?=$this->load->view('service_request/details/bids', array("serviceRequest" => $serviceRequest, "bids" => $bids, "isCreator" => $isCreator))?>
		
		<?=$this->load->view('service_request/details/messages', array("serviceRequest" => $serviceRequest, "messageThreads" => $messageThreads, "isCreator" => $isCreator))?>

		<?php if($showCreateMessage) { ?>
			<div id="divCreateSRMessage" class="SectionBox">
				<div class="Header">Send the buyer a message</div>
				<?=$this->load->view('service_request/details/create_message', array("serviceRequest" => $serviceRequest, "type" => "service_request", "id" => $serviceRequest->id));?>
			</div>
		<?php } else if(!$isLoggedIn) { ?>
			<div class="SectionBox">
				<div class="Header">Send Message</div>
				<div>To send a message please <a href="/auth/login?destination=<?=uri_string()?>">login</a></div>
			</div>
		<?php } else if($user->isBuyer() && !$isCreator && empty($messageThreads) && empty($bids)) { ?>
			<div class="SectionBox">
				<div class="Header">Submit Message</div>
				<div>You have to be a seller to send a message</div>
			</div>
		<?php } ?>
		
		<?php if($showCreateBid) { ?>
			<?=$this->load->view('service_request/details/create_bid', array("serviceRequest" => $serviceRequest))?>
		<?php } else if(!$isLoggedIn) { ?>
			<div class="SectionBox">
				<div class="Header">Submit Bid</div>
				<div>To submit a bid please <a href="/auth/login?destination=<?=uri_string()?>">login</a></div>
			</div>
		<?php } else if($user->isBuyer() && !$isCreator && empty($bids)) { ?>
			<div class="SectionBox">
				<div class="Header">Submit Bid</div>
				<div>You have to be a seller to submit a bid</div>
			</div>
		<?php } ?>
		
		<?php if($showServiceRequestModify) {
			$this->load->view('service_request/details/modify', array("serviceRequest" => $serviceRequest));
		}?>
</div>

<script type="text/javascript" src="http://maps.google.com/maps?file=api&v=2&key=<?=$this->config->item("google_key")?>"></script>
<?php $this->carabiner->js("service_request/details.js"); ?>

<script type="text/javascript">
<!--

	$(document).ready(function(){
		
		createMap(<?=$latitude?>, <?=$longitude?>, document.getElementById("divMapContainer"));
	});
	
//-->
</script>

<?=$this->load->view('footer')?>