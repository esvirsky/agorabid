<?=$this->load->view('header')?>

<div id="divForgotPassword" class="SectionBox">
	<div class="Header">Forgot Password</div>
	<form id="frmForgotPassword" name="frmForgotPassword" action="/auth/forgot_password" method="POST"/>
		<div id="divError" class="FormError" style="display: <?= isset($formError) ? "" : "none" ?>;"><?=isset($formError) ? $formError : ""?></div>
	
		<table cellspacing="0" cellpadding="0">
			<tr><td><label for="txtUsername">Username<span class="RequiredStar">*</span></label></td><td><input id="txtUsername" name="txtUsername" class="required" type="text" /></td></tr>
			<tr><td colspan="2"><button id="btnSubmit" type="submit">Submit</button></td></tr>		
		</table>
	</form>
</div>


<script>
<!--
$(document).ready(function(){
		
		$("#frmForgotPassword").validate({
			onkeyup: false,
			errorPlacement: function(error, element)
			{	
			     error.prependTo( element.parent());
			}
		});
});
//-->
</script>

<?=$this->load->view('footer')?>  