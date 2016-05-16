<?=$this->load->view('header')?>

<?php 

$reviewBids = $this->bidlib->getUnreviewedBidsByUser($this->userlib->getLoggedInUser());

?>

<div id="divUserManager">

	<div class="Header">Account Manager</div>
	<p>Welcome to your account manager</p>

	<?=$this->load->view('user/manager/pending_reviews')?>

	<?=$this->load->view('user/manager/notifications')?>

	<?=$this->load->view('user/manager/service_requests')?>

	<?=$this->load->view('user/manager/bids')?>

	<?=$this->load->view('user/manager/info')?>

</div>

<?php $this->carabiner->js("user/manager.js"); ?>

<?=$this->load->view('footer')?>