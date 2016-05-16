<?=$this->load->view('header')?>

<?php $user = $this->userlib->getLoggedInUser(true); ?>

<?php if($user->isBuyer()) { ?><style>#divNotificationManager { width: 334px; } </style><?php } ?>

<div id="divNotificationManager">
	<div class="Header">Email Notification Settings</div>
	<form id="frmSRNotify" name="frmSRNotify" action="/user/notification_manager" method="POST">
		<div id="divFormError" class="FormError" style="display: <?= isset($formError) ? "" : "none" ?>;"><?=isset($formError) ? $formError : ""?></div>
		<div id="divNotifications" class="SectionBox">
			<table cellspacing="0" cellpadding="0">
				<tr>
					<td><label for="chkNewBidNotify">Email me when I receive a new bid?</label></td>
					<td><input id="chkNewBidNotify" name="chkNewBidNotify" type="checkbox" <?=$user->info->newBidNotify == true ? "checked" : "" ?>/></td>
				</tr>
				<tr>
					<td><label for="chkNewMessageNotify">Email me when I receive a new message?</label></td>
					<td><input id="chkNewMessageNotify" name="chkNewMessageNotify" type="checkbox" <?=$user->info->newMessageNotify == true ? "checked" : "" ?>/></td>
				</tr>
				<?php if($user->isSeller()) { ?>
					<tr>
						<td><label for="chkBidAcceptedNotify">Email me when my bid status changes?</label></td>
						<td><input id="chkBidAcceptedNotify" name="chkBidAcceptedNotify" type="checkbox" <?=$user->info->bidAcceptedNotify == true ? "checked" : "" ?>/></td>
					</tr>
				<?php } ?>
			</table>
		</div>	
		
		<?php if($user->isSeller()) { ?>
			<div id="divSRNotify" class="SectionBox">
				<div class="SubHeader1">Service Request Notifications ( for Sellers )</div>
				<p>Do you want to be notified by email of any new service requests in your area (based on the locations and categories you selected)?</p>
	
				<div><label id="lblSRNotify">Notify me of a service request in my area<span class="RequiredStar">*</span></label></div>
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td><label for="rdbSRNotify1">Within a radius of my locations</label></td>
						<td><input id="rdbSRNotify1" name="rdbSRNotify" value="radius" type="radio" class="required" <?=$user->info->srNotify == "radius" ? "checked" : "" ?>/></td>
						<td id="tdRadius"><input id="txtRadius" name="txtRadius" type="text" class="digits" <?=$user->info->srNotify == "radius" ? "value='" . $user->info->srNotifyRadius . "'" : "" ?>/></td>
						<td><label id="lblSRNotifyUnits">Miles</label></td>
					</tr>
					<tr>
						<td><label for="rdbSRNotify2">Anywhere</label></td>
						<td><input id="rdbSRNotify2" name="rdbSRNotify" value="all" type="radio" class="required" <?=$user->info->srNotify == "all" ? "checked" : "" ?>/></td>
					</tr>
					<tr>
						<td><label for="rdbSRNotify3">Please don't notify me</label></td>
						<td><input id="rdbSRNotify3" name="rdbSRNotify" value="none" type="radio" class="required" <?=$user->info->srNotify == "none" ? "checked" : "" ?>/></td>
					</tr>
				</table>
			</div>
		<?php } ?>
		
		<input id="hdnDummy" name="hdnDummy" type="hidden" />
		<button id="btnSave" type="submit" class="SubmitButton">Save Notifications</button>
	</form>
	
</div>


<?php $this->carabiner->js("user/notification_manager.js"); ?>

<?=$this->load->view('footer')?>