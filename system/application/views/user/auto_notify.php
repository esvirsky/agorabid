<?=$this->load->view('header')?>

<?php $user = $this->userlib->getLoggedInUser(true); ?>

<div id="divAutoNotifyManager" class="SectionBox">
	<div class="Header">Auto Notify</div>
	<p>Do you want to be notified of any new service requests in your area ( based on the locations and categories you selected )?</p>
	<form id="frmAutoNotify" name="frmAutoNotify" action="/user/auto_notify" method="POST">
		<div id="divFormError" class="FormError" style="display: <?= isset($formError) ? "" : "none" ?>;"><?=isset($formError) ? $formError : ""?></div>
		
		<div><label id="lblAutoNotify">Notify me of a service request in my area<span class="RequiredStar">*</span></label></div>
		<table cellspacing="0" cellpadding="0">
			<tr>
				<td><label for="rdbAutoNotify1">Within a radius of my locations</label></td>
				<td><input id="rdbAutoNotify1" name="rdbAutoNotify" value="radius" type="radio" class="required" <?=$user->info->autoNotify == "radius" ? "checked" : "" ?>/></td>
				<td id="tdRadius"><input id="txtRadius" name="txtRadius" type="text" class="number" <?=$user->info->autoNotify == "radius" ? "value='" . $user->info->autoNotifyRadius . "'" : "" ?>/></td>
				<td><label id="lblAutoNotifyUnits">Miles</label></td>
			</tr>
			<tr>
				<td><label for="rdbAutoNotify2">Anywhere</label></td>
				<td><input id="rdbAutoNotify2" name="rdbAutoNotify" value="all" type="radio" class="required" <?=$user->info->autoNotify == "all" ? "checked" : "" ?>/></td>
			</tr>
			<tr>
				<td><label for="rdbAutoNotify3">Please don't notify me</label></td>
				<td><input id="rdbAutoNotify3" name="rdbAutoNotify" value="none" type="radio" class="required" <?=$user->info->autoNotify == "none" ? "checked" : "" ?>/></td>
			</tr>
			<tr>
				<td colspan="4"><button id="btnSubmit" type="submit">Save</button></td>
			</tr>
		</table>
	</form>
</div>


<?php $this->carabiner->js("user/auto_notify.js"); ?>

<?=$this->load->view('footer')?>