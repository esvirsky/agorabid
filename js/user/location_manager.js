function LocationManager(locations, require)
{
	this.require = require;
	var locationManager = this;
	$("#btnAddLocation").bind("click", function(e) { locationManager.addLocation(); });
	for(var i=0; i<locations.length; i++)
		this.addLocation(locations[i]);
}

LocationManager.prototype.primaryLocationChange = function(event)
{
	var jq = $("#" + event.target.id);
	if(jq.is(":checked"))
	{
		$("#divLocationManager .primaryCheckbox").each(function(){ $("#" + this.id).attr("checked", false);  });
		jq.attr("checked", true);
	}
}

LocationManager.prototype.addLocation = function(location)
{
	var locationManager = this;
	
	var id = $("#hdnLastLocationId").val();
	var html = $("#divLocationTemplate").html().replace(/{NUMBER}/g, id)
												.replace(/ddnCountry/g, "ddnCountry" + id)
												.replace(/ddnState/g, "ddnRegion" + id);
	var div = $(html);
	$("#divLocationManager").append(div);
	$("#btnRemoveLocation" + id).click(function(e) { locationManager.removeLocation(e); });
	$("#chkPrimary" + id).change(function(e) { locationManager.primaryLocationChange(e); });
	$("#ddnCountry" + id).val("US");
	$("#hdnLastLocationId").val(parseInt(id) + 1);

	if(location != null && location.type != 'click')
	{
		$("#txtStreet1" + id).val(location.street1);
		$("#txtStreet2" + id).val(location.street2);
		$("#txtCity" + id).val(location.city);
		$("#ddnRegion" + id).val(location.region);
		$("#txtPostalCode" + id).val(location.postalCode);
		$("#ddnCountry" + id).val(location.country);
		$("#txtLocationPhone" + id).val(location.phone);
		$("#txtLocationFax" + id).val(location.fax);
		$("#txtLocationName" + id).val(location.name);
		$("#txtLocationDescription" + id).val(location.description);
		$("#txtLocationEmail" + id).val(location.email);
		$("#txtLocationWebsite" + id).val(location.website);
		$("#hdnLocationId" + id).val(location.id);
		$("#chkPrimary" + id).attr("checked", location.primary > 0? true : false);
	}
	
	if(this.require && $("#divLocationManager .SectionBox").length > 1)
		$(".RemoveLocationButton").show();
}

LocationManager.prototype.removeLocation = function(event)
{
	var id = event.target.id.replace(/btnRemoveLocation/, "");
	if(!confirm("Are you sure you want to remove this location?"))
		return;

	$("#divLocation" + id).remove();
	
	if(this.require && $("#divLocationManager .SectionBox").length < 2)
		$(".RemoveLocationButton").hide();
}

LocationManager.prototype.saveLocations = function()
{
	var locations = new Array();
	$("#divLocationManager div").each(function(element){

		if(!this.id.match(/divLocation\d+/))
			return;
		
		var id = this.id.replace(/divLocation/, "");
		var location = new Object();
		location.primary = $("#chkPrimary" + id).attr("checked");
		location.name = $("#txtLocationName" + id).val();
		location.street1 = $("#txtStreet1" + id).val();
		location.street2 = $("#txtStreet2" + id).val();
		location.city = $("#txtCity" + id).val();
		location.region = $("#ddnRegion" + id).val();
		location.postalCode = $("#txtPostalCode" + id).val();
		location.country = $("#ddnCountry" + id).val();
		location.phone = $("#txtLocationPhone" + id).val();
		if($("#hdnLocationId" + id).val() != undefined)
			location.id = $("#hdnLocationId" + id).val();
		
		if($("#txtLocationDescription" + id).val() != undefined)
		{
			location.description = $("#txtLocationDescription" + id).val();
			location.email = $("#txtLocationEmail" + id).val();
			location.website = $("#txtLocationWebsite" + id).val();
			location.fax = $("#txtLocationFax" + id).val();
		}
		
		locations.push(location);
	});

	$("#hdnLocations").val($.toJSON(locations));
	$("#divLocationTemplate").remove();
}

$(document).ready(function(){

	$("#frmLocationManager").validate({
		validateHidden: false,
		submitHandler: submitHandler,
		showErrors: function(errorMap, errorList) 
		{ 
			if(this.numberOfInvalids() > 0) 
			{ 
				$("#divFormError").html("The highlighted fields below are either missing or invalid"); 
				$("#divFormError").show(); 
			}
			this.defaultShowErrors();
		},
		errorPlacement: function(error, element) {
			error.appendTo($(element.form).find("label[for=" + element.attr("id") + "]").parent("td"));
		},
		highlight: function(element, errorClass)
		{
	    	$("#" + element.id).addClass("ValidationError");
		},
		unhighlight: function(element, errorClass)
		{
			$("#" + element.id).removeClass("ValidationError");
		}
	});
	
	function submitHandler(form)
	{
		$.locationManager.saveLocations();
		form.submit();
	}
});