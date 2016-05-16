<?=$this->load->view('header')?>  

<div id="divRegister" class="SectionBox">
	<div class="Header">Create a New Account</div>
	<form id="frmRegister" name="frmRegister" action="/auth/register" method="POST"/>
		<table cellspacing="0" cellpadding="0">
			<tr><td><label for="txtUsername">Username<span class="RequiredStar">*</span></label></td><td><input id="txtUsername" name="txtUsername" class="required" type="text" /></td></tr>
			<tr id="trPassword"><td><label for="txtPassword">Password<span class="RequiredStar">*</span></label></td><td><input id="txtPassword" name="txtPassword" class="required" type="password" /></td></tr>
			<tr><td><label for="txtPassword2">Confirm Password<span class="RequiredStar">*</span></label></td><td><input id="txtPassword2" name="txtPassword2" class="required" type="password" /></td></tr>
			<tr id="trEmail"><td><label for="txtEmail">Email<span class="RequiredStar">*</span></label></td><td><input id="txtEmail" name="txtEmail" class="required" type="text" /></td></tr>
			<tr><td><label for="txtEmail2">Confirm Email<span class="RequiredStar">*</span></label></td><td><input id="txtEmail2" name="txtEmail2" class="required" type="text" /></td></tr>
			<tr><td colspan="2"><button id="btnRegister" type="submit">Register</button></td></tr>		
		</table>
	</form>
</div>

<?php $this->carabiner->js("auth/register.js"); ?>

<?=$this->load->view('footer')?>  