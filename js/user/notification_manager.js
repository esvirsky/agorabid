$(document).ready(function(){

	$("#frmSRNotify").validate({
		onkeyup: false,
		submitHandler: submitHandler,
		errorPlacement: function(error, element)
		{	
		     error.prependTo( element.parent());
		}
	});
	
	$("#rdbSRNotify1").bind("click", srNotifyChange);
	$("#rdbSRNotify2").bind("click", srNotifyChange);
	$("#rdbSRNotify3").bind("click", srNotifyChange);
	
	if($("#rdbSRNotify1").length > 0)
		srNotifyChange($("#rdbSRNotify1").is(":checked"));
	
	function srNotifyChange(radius)
	{
		if(radius == true || this.value == "radius")
		{
			$("#txtRadius").rules("add", "required");
			$("#txtRadius").removeAttr("disabled");
		}
		else
		{
			$("#txtRadius").rules("remove", "required");
			$("#txtRadius").attr("disabled", true);
		}
	}
	
	function submitHandler(form)
	{
		$("#divTopMessage").hide();
		form.submit();
	}
});