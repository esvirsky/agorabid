$(document).ready(function(){

	$("#frmInfo").validate({
		validateHidden: false,
		onkeyup: false,
		errorPlacement: function(error, element)
		{	
		     error.prependTo( element.parent());
		}
	});
	
	$("#rdbUserType1").bind("click", userTypeChange);
	$("#rdbUserType2").bind("click", userTypeChange);
	$("#rdbUserType3").bind("click", userTypeChange);

	$("#divBuyerInfo").hide();
	$("#divSellerInfo").hide();
	if($("#rdbUserType2").is(":checked") || $("#rdbUserType3").is(":checked"))
		$("#divSellerInfo").show();
	else if($("#rdbUserType1").is(":checked"))
		$("#divBuyerInfo").show();
	
	function userTypeChange()
	{
		if(this.value == "buyer")
			$("#divSellerInfo").hide("normal", userTypeChange2);
		else
			$("#divBuyerInfo").hide("normal", userTypeChange2);
	}

	function userTypeChange2()
	{
		if($("#rdbUserType2").is(":checked") || $("#rdbUserType3").is(":checked"))
			$("#divSellerInfo").show("normal");
		else
			$("#divBuyerInfo").show("normal");
	}
});