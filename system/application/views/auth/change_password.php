<?=$this->load->view('header')?>

<div id="divChangePassword" class="SectionBox">
	<div class="Header">Change Password</div>
	<form id="frmChangePassword" name="frmChangePassword" action="/auth/change_password" method="POST"/>
		<div class="FormError" style="display: <?= isset($formError) ? "" : "none" ?>;"><?=isset($formError) ? $formError : ""?></div>
		<table cellspacing="0" cellpadding="0">
			<tr><td><label for="txtOldPassword">Old Password<span class="RequiredStar">*</span></label></td><td><input id="txtOldPassword" name="txtOldPassword" class="required" type="password" /></td></tr>
			<tr><td><label for="txtPassword">New Password<span class="RequiredStar">*</span></label></td><td><input id="txtPassword" name="txtPassword" class="required" type="password" /></td></tr>
			<tr><td><label for="txtPassword2">Confirm Password<span class="RequiredStar">*</span></label></td><td><input id="txtPassword2" name="txtPassword2" class="required" type="password" /></td></tr>
			<tr><td colspan="2"><button id="btnSubmit" type="submit">Change</button></td></tr>		
		</table>
	</form>
</div>

<?php $this->carabiner->js("auth/change_password.js"); ?>

<?=$this->load->view('footer')?>  