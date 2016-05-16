<?=$this->load->view('header')?>

<div id="divForgotUsername" class="SectionBox">
	<div class="Header">Forgot Username</div>
	<form id="frmForgotUsername" name="frmForgotUsername" action="/auth/forgot_username" method="POST"/>
		<table cellspacing="0" cellpadding="0">
			<tr><td><label for="txtEmail">Email<span class="RequiredStar">*</span></label></td><td><input id="txtEmail" name="txtEmail" class="required email" type="text" /></td></tr>
			<tr><td colspan="2"><button id="btnSubmit" type="submit">Submit</button></td></tr>		
		</table>
	</form>
</div>


<script>
<!--
$(document).ready(function(){

	$("#frmForgotUsername").validate({
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