$(document).ready(function(){
		
		$("#btnBack").click(back);
		$("#btnContinue").click(submit);
		$("#btnSubmit").click(verify);
		
		back();
		
		$("#frmChangeEmail").validate({
				onkeyup: false,
				errorPlacement: function(error, element)
				{	
				     error.prependTo( element.parent());
				},
				rules: 
				{
					txtEmail: { remote: { url: "/auth/check_email", type: "post" } },
					txtEmail2: { equalTo: "#txtEmail" }
				},
				messages: 
				{
					txtEmail: { remote: jQuery.format("This email address is already in use") },
					txtEmail2: { equalTo: "Emails do not match" }
				}
		});
		
		function verify()
		{
			if(!$("#frmChangeEmail").valid())
				return;
		
			$("#txtEmail").attr("disabled", true);
			$("#txtEmail2").attr("disabled", true);
			$("#btnSubmit").attr("disabled", true);
			$("#divLogoutWarning").show();
		}
		
		function back()
		{
			$("#txtEmail").attr("disabled", false);
			$("#txtEmail2").attr("disabled", false);
			$("#btnSubmit").attr("disabled", false);
			$("#divLogoutWarning").hide();
		}
		
		function submit()
		{
			$("#txtEmail").attr("disabled", false);
			$("#txtEmail2").attr("disabled", false);
			$("#frmChangeEmail").submit();
		}
});