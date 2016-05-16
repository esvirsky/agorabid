function highlightLocation(id)
{
	//IE 8 hack
	$("#divLocationList").css("margin-right", "8px");
	if($.browser.msie)
	{
		$("#divLocationList .SectionBox").css( { "border-width": "", "border-style" : "", "border-color" : "" });
		$("#divLocation" + id).css({ "border-width": "2", "border-style" : "dashed", "border-color" : "#cc0000" });
	}
	else
	{
		$("#divLocationList .SectionBox").css("border", "");
		$("#divLocation" + id).css("border", "2px dashed #cc0000");
	}
	
	window.scroll(0, document.getElementById('divLocation' + id).offsetTop-50);
}

$(document).ready(function(){

	$(".LocationDetails").hide(); // IE 8 hack
	$(".LocationDetailsToggle").bind("click", toggleDetails);
	$(".ShowOnMapButton").click(showOnMap);
	
	createFoldout($("#divReviews"), $("#divReviewsToggle"));
	createFoldout($("#divLocations"), $("#divLocationsToggle"));

	function toggleDetails()
	{
		var id = this.id.replace(/divLocationDetailsToggle/, "");
		
		// IE 8 hack
		var elem = $(".LocationDetails" + id)[0];
		if(elem.style.display == "none")
		     $(".LocationDetails" + id).show();
		else
		     $(".LocationDetails" + id).hide();

		// IE 8 hack
		$(this).find("img").attr("src", "/images/" + ( $(".LocationDetails" + id)[0].style.display == "none" ? "collapsed.gif" : "expanded_up.gif"));
	}
	
	function showOnMap()
	{
		var id = this.id.replace(/btnShowOnMap/, "");
		$.Map.gotoPoint(id);
	}
});

$(window).unload( function () { GUnload(); } );