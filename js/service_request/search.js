$(document).ready(function(){

	$("#formSearch").validate({
		onkeyup: false,
		errorPlacement: function(error, element)
		{	
			if(element.parent().attr("class") == "mcdropdown")
				error.prependTo( element.parent().parent().parent());
			else
				error.prependTo( element.parent());
		},
		rules:
		{
			category: { min: 1 }
		},
		messages:
		{
			category: { min: "This field is required." },
			radius: { required: "required", digits: "Digits only" }
		}
	});
	
	$("#everywhere").click(changeEverywhere);
	$(".ShowOnMapButton").click(showOnMap);

	changeEverywhere();

	function changeEverywhere()
	{
		var checked = $("#everywhere").is(":checked");
		$("#address").attr("disabled", checked);
		$("#radius").attr("disabled", checked);
		$("#address").rules(checked ? "remove" : "add", "required");
		$("#radius").rules(checked ? "remove" : "add", "required");
		$("#radius").rules(checked ? "remove" : "add", "digits");
	}
	
	function showOnMap()
	{
		$.Map.gotoPoint(this.id.replace(/btnShowOnMap/, ""));
	}
	
});

$(window).unload( function () { if(typeof(GUnload) != 'undefined') GUnload(); } );