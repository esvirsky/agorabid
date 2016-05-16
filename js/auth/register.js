$(document).ready(function(){

	// Needed for username validation
	jQuery.validator.addMethod("alphanumeric_period", function(value, element) {
		return this.optional(element) || /^[\w\.]+$/i.test(value);
	}, "Letters, numbers, periods and underscores only please");  

	$("#frmRegister").validate({
		onkeyup: false,
		validateHidden: false,
		groups: 
		{
			passwordFields: "txtPassword txtPassword2",
			emailFields: "txtEmail txtEmail2"
		},
		highlight: function(element, errorClass)
		{
	    	$(element.form).find("label[for=" + element.id + "]").addClass("ValidationError");
		},
		unhighlight: function(element, errorClass)
		{
			$(element.form).find("label[for=" + element.id + "]").removeClass("ValidationError");
		},
		errorPlacement: function(error, element)
		{	
		     error.prependTo( element.parent());
		},
		rules: 
		{
			txtUsername: { alphanumeric_period: true, remote: { url: "/auth/check_username", type: "post" } },
			txtEmail: { email: true, remote: { url: "/auth/check_email", type: "post" } },
			txtEmail2: { email: true, equalTo: "#txtEmail" },
			txtPassword: { rangelength: [6,20] },
			txtPassword2: { rangelength: [6,20], equalTo: "#txtPassword"}
		},
		messages: 
		{
			txtPassword2: { equalTo: "Passwords do not match" },
			txtUsername: { alphanumeric_period: "Username has invalid characters<br/>Please use letters, numbers, underscores, and periods only", remote: jQuery.format("Username is already in use") },
			txtEmail: { remote: jQuery.format("Email is already in use") },
			txtEmail2: { equalTo: "Emails do not match" }
		}
	});
});