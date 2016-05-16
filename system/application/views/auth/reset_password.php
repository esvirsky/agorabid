<?=$this->load->view('header')?>

<div id="divResetPassword" class="SectionBox StandardFormSection">
	<div class="Header">Reset Password</div>
	<form id="frmResetPassword" name="frmResetPassword" action="<?=$_SERVER["REQUEST_URI"]?>" method="POST"/>
		<table cellspacing="0" cellpadding="0">
			<tr><td><label for="txtPassword">New Password</label></td><td><input id="txtPassword" name="txtPassword" class="required" type="password" /></td></tr>
			<tr><td><label for="txtPassword2">Confirm Password</label></td><td><input id="txtPassword2" name="txtPassword2" class="required" type="password" /></td></tr>
			<tr><td colspan="2"><button id="btnSubmit" name="btnSubmit" type="submit">Submit</button></td></tr>
		</table>
	</form>
</div>

<?php $this->carabiner->js("auth/reset_password.js"); ?>

<?=$this->load->view('footer')?>  