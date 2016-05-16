function RatingSelector(inputName, jqDisplay)
{
	this.inputName = inputName;
	this.jqDisplay = jqDisplay;
	
	var ratingSelector = this;
	var rating = $("input[name='" + inputName + "']").rating({
		required: true,
	  	focus: function(value, link){
		ratingSelector.clear();
	  		jqDisplay.text(link.title);
	  	},
	 	blur: function(value, link){
	  		ratingSelector.setDisplay();
	 	}
	 });
}

RatingSelector.prototype.clear = function()
{
	$("label[for='" + this.inputName + "']").remove();
}

RatingSelector.prototype.setDisplay = function()
{
	var jq = $("input[name='" + this.inputName + "']:checked");
	this.jqDisplay.text(jq.attr("title") == undefined ? "" : jq.attr("title"));
}
