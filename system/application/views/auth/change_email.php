<?=$this->load->view('header')?>

<div id="divChangeEmail" class="SectionBox">
	
	<div class="Header">Change Email</div>
	<form id="frmChangeEmail" name="frmChangeEmail" action="/auth/change_email" method="POST"/>
		<table cellspacing="0" cellpadding="0">
			<tr><td><label for="txtEmail">New Email<span class="RequiredStar">*</span></label></td><td><input id="txtEmail" name="txtEmail" class="required email" type="text" /></td></tr>
			<tr><td><label for="txtEmail2">Confirm Email<span class="RequiredStar">*</span></label></td><td><input id="txtEmail2" name="txtEmail2" class="required email" type="text" /></td></tr>
			<tr><td colspan="2"><button id="btnSubmit" type="button">Change</button></td></tr>		
		</table>
	</form>
	
	<div id="divLogoutWarning">
		<p><b>Note:</b> Changing your email will log you out. You will not be able to log back in until your email is verified</p>
		<div id="divLogoutWarningButtons">
			<button id="btnBack" type="button" />Cancel</button>
			<button id="btnContinue" type="button" />Continue</button>
		</div>
		<div class="Clear">&nbsp;</div>
	</div>
</div>

<?php $this->carabiner->js("auth/change_email.js"); ?>

<?=$this->load->view('footer')?>  