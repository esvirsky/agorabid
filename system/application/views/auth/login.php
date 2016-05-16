<?=$this->load->view('header')?>

<div id="divRegisterInfo" class="SectionBox">

	<div class="Header">Register</div>
	
	<p><b><?= $this->config->item("site_domain")?></b> makes it easy to find service providers and negotiate services.
	Join today to start	creating service requests.</p>

	<div id="divRegisterButton" class="BigButton"><a href="/auth/register"><img src="/images/big_button_register.png" alt="Register" /></a></div>	

</div>

<div id="divLogin" class="SectionBox">
	<form id="frmLogin" name="frmLogin" action="<?=$_SERVER["REQUEST_URI"]?>" method="POST"/>
		<div id="divFormError" class="FormError" style="display: <?= isset($formError) ? "" : "none" ?>;"><?=isset($formError) ? $formError : ""?></div>
		
		<table cellspacing="0" cellpadding="0">
			<tr><td><label for="txtUsername">Username</label></td><td><input id="txtUsername" name="txtUsername" class="required" type="text" value="<?=set_value("txtUsername")?>" /></td></tr>
			<tr><td><label for="txtPassword">Password</label></td><td><input id="txtPassword" name="txtPassword" class="required" type="password" /></td></tr>
			<tr><td>&nbsp;</td><td><input id="chkRemember" name="chkRemember" type="checkbox" class="CheckBox" value="1" <?=set_checkbox("chkRemember", "1")?>/><label id="lblRemember" for="chkRemember">Remember me</label></td></tr>
			<tr><td colspan="2"><button id="btnSignIn" name="btnSignIn" type="submit">Login</button></td></tr>		
		</table>
	</form>
	
	<div id="divForgotLinks">
		<div><a href="/auth/forgot_username">Forgot Username</a></div>
		<div><a href="/auth/forgot_password">Forgot Password</a></div>
	</div>	
</div>



<script>
<!--

$(document).ready(function(){

		$("#frmLogin").validate({
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			showErrors: function(errorMap, errorList) { if(this.numberOfInvalids() > 0) { $("#divFormError").text("Username and password are required"); $("#divFormError").show(); }}
		});
});

//-->
</script>

<?=$this->load->view('footer')?>  