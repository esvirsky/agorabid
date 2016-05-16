$(document).ready(function(){

	$("#frmServiceRequest").validate({
		validateHidden: false,
		onkeyup: false,
		errorPlacement: function(error, element)
		{	
		     error.prependTo( element.parent());
		}
	});

	$("#rdbSRLocationMine").bind("click", rdbSRLocationChange);
	$("#rdbSRLocationTheir").bind("click", rdbSRLocationChange);
	$("#divLocationAddress").hide();
	
	if($("#rdbSRLocationMine").is(":checked") || $("#rdbSRLocationTheir").is(":checked"))
		rdbSRLocationChange($("#rdbSRLocationMine").is(":checked") ? "mine" : "their");
	
	function rdbSRLocationChange(value)
	{
		value = this.value == null ? value : this.value;
		if(value == "mine")
			$("#pLocationAddress").text("Please give us your location");
		else
			$("#pLocationAddress").text("Please give us your starting location");
			
		$("#divLocationAddress").show();
	}
});