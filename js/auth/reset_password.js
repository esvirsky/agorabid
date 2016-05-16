$("#frmResetPassword").validate({
	onkeyup: false,
	errorPlacement: function(error, element)
	{	
	     error.prependTo( element.parent());
	},
	rules: 
	{
		txtPassword: { rangelength: [6,20] },
		txtPassword2: { rangelength: [6,20], equalTo: "#txtPassword" }
	},
	messages: 
	{
		txtPassword2: { equalTo: "Passwords do not match" }
	}
});