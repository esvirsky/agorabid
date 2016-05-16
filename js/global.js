function createFoldout(targetElement, toggleElement)
{
	toggleElement.find("img").attr("src", "/images/" + ( targetElement.is(":hidden") ? "collapsed.gif" : "expanded.gif"));
	toggleElement.find("a").bind("click", function(){ toggleFoldout(targetElement, toggleElement); });
}

function toggleFoldout(targetElement, toggleElement)
{
	targetElement.toggle(); 
	toggleElement.find("img").attr("src", "/images/" + ( targetElement.is(":hidden") ? "collapsed.gif" : "expanded.gif")); 
}

$(document).ready(function(){

	var selected = $("#hdnHeaderMenuSelected").val();
	
	$("#imgMenuHome").hover( over, out);
	$("#imgMenuNew").hover( over, out);
	$("#imgMenuSearch").hover( over, out);

	if ($.browser.msie && $.browser.version.substr(0,1)<7)
		$("#divIE6").css("display", "block");
	
	function over()
	{
		if(this.id == selected)
			return;
		
		switch(this.id)
		{
			case "imgMenuHome":
				$("#imgMenuHome").attr("src", "/images/menu_home_selected.png");
				break;
			case "imgMenuNew":
				$("#imgMenuNew").attr("src", "/images/menu_new_selected.png");
				break;
			case "imgMenuSearch":
				$("#imgMenuSearch").attr("src", "/images/menu_search_selected.png");
				break;		
		}
	}

	function out()
	{
		if(this.id == selected)
			return;
		
		switch(this.id)
		{
			case "imgMenuHome":
				$("#imgMenuHome").attr("src", "/images/menu_home.png");
				break;
			case "imgMenuNew":
				$("#imgMenuNew").attr("src", "/images/menu_new.png");
				break;
			case "imgMenuSearch":
				$("#imgMenuSearch").attr("src", "/images/menu_search.png");
				break;		
		}
	}
});