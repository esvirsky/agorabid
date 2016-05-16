function createMap(latitude, longitude, containingElement)
{
	var map = new GMap2(containingElement);
	var gLatLngBounds = new GLatLngBounds();
	var gLatLng = new GLatLng(latitude, longitude);
	map.addOverlay(new GMarker(gLatLng));
	gLatLngBounds.extend(gLatLng);	
	map.setCenter(gLatLngBounds.getCenter(), 13);
	map.setUIToDefault();
}

$(document).ready(function(){

	$(".BidMessagesToggle a").bind("click", toggleBidMessages);
	$(".MessageThreadToggle a").bind("click", toggleMessageThread);
	$(".MessageThreadReplyButton").bind("click", toggleCreateThreadMessage);
	$(".AcceptBidButton").click(acceptBid);
	$("#txtPrice").change(priceChange);
	$("#txtTime").change(timeChange);
	
	$("#btnEndBidding").click(endBidding);
	$("#btnModify").click(modifySr);
	$("#btnDelete").click(deleteSr);
	$("#btnReopen").click(reopenSr);

	createFoldout($("#divBids"), $("#divBidsToggle"));
	createFoldout($("#divMessages"), $("#divMessagesToggle"));
	
	$(".FormCreateMessage").each(function(i) { 
		$("#" + this.id).validate({
			onkeyup: false,
			errorPlacement: function(error, element)
			{
				error.prependTo(element.parent());
			}
		});
	});
	
	if($("#formBid").length > 0)
	{
		$("#formBid").validate({
			onkeyup: false,
			submitHandler: bidSubmitHandler,
			errorPlacement: function(error, element)
			{	
				error.prependTo(element.parent());
			}
		});
		
		priceChange();
		timeChange();
	}
	
	unfoldAnchor();
	
	function priceChange()
	{
		$("input[name='rdbPricePrecision']").rules($("#txtPrice").val() == "" ? "remove" : "add", "required");
		$("#lblPricePrecision span").css("display", $("#txtPrice").val() == "" ? "none" : "inline");
		$("#lblPricePrecision").css("padding-right", $("#txtPrice").val() == "" ? "7px" : "0px");
	}

	function timeChange()
	{
		$("input[name='rdbTimePrecision']").rules($("#txtTime").val() == "" ? "remove" : "add", "required");
		$("#lblTimePrecision span").css("display", $("#txtTime").val() == "" ? "none" : "inline");
		$("#lblTimePrecision").css("padding-right", $("#txtTime").val() == "" ? "7px" : "0px");
	}

	function bidSubmitHandler(form)
	{
		if($("#txtPrice").val() == "" && $("#txtTime").val() == "" && $("#txtNote").val() == "")
		{
			$("#formBid #divFormError").text("Please fill out at least one field - price, time, or note");
			$("#formBid #divFormError").css("display", "");
			return false;
		}
			
		form.submit();
	}

	function toggleBidMessages()
	{
		var id = $(this).parent().attr("id").replace(/divBidMessagesToggle/, "");
		$("#divBidMessageContainer" + id).toggle();
		$(this).find("img").attr("src", "/images/" + ( $("#divBidMessageContainer" + id).is(":hidden") ? "collapsed.gif" : "expanded.gif"));
	}
	
	function toggleMessageThread()
	{
		var id = $(this).parent().attr("id").replace(/divMessageThreadToggle/, "");
		$("#divMessageThread" + id).toggle();
		$(this).find("img").attr("src", "/images/" + ( $("#divMessageThread" + id).is(":hidden") ? "collapsed.gif" : "expanded.gif"));
	}
	
	function toggleCreateThreadMessage()
	{
		var id = this.id.replace(/btnMessageThreadReply/, "");
		$("#divCreateThreadMessage" + id).show(1, function() { $("#btnMessageThreadReply" + id).hide() });
	}
	
	function acceptBid()
	{
		if(!confirm("Once you accept this bid, all of your personal information, "
					+ "along with the exact address will be shown to the accepted bidder. Do you want "
					+ "to continue?"))
			return false;
		
		var id = this.id.replace(/btnAcceptBid/, "");
		self.location = "/service_request/accept_bid/" + id;
	}

	function endBidding()
	{
		if(!confirm("Are you sure that you want to end bidding for this service request?"))
			return false;
		
		var id = $("#hdnServiceRequestId").val();	
		self.location = "/service_request/end_bidding/" + id;
	}
	
	function modifySr()
	{
		var id = $("#hdnServiceRequestId").val();
		self.location = "/service_request/modify/" + id;
	}
	
	function deleteSr()
	{
		if(!confirm("Are you sure that you want to delete this service request?"))
			return false;
		
		var id = $("#hdnServiceRequestId").val();
		self.location = "/service_request/delete/" + id;
	}
	
	function reopenSr()
	{
		if(!confirm("Are you sure that you want to reopen this service request for bidding?"))
			return false;
		
		var id = $("#hdnServiceRequestId").val();
		self.location = "/service_request/reopen/" + id;
	}
	
	function unfoldAnchor()
	{
		var anchor = window.location.href.match(/#.*/);
		if(anchor != null)
		{
			var full = anchor[0].replace(/^#/, "");
			var type = full.replace(/\d+$/, "");
			var num = full.replace(type, "");
			
			if(type == "bid" && $("#divBids").css("display") == "none")
				toggleFoldout($("#divBids"), $("#divBidsToggle"));
			else if(type == "message")
			{
				var messageContainer = $("a[name='" + full + "']").parents(".BidMessageContainer");
				var messageThread = $("a[name='" + full + "']").parents(".MessageThread");
				if(messageContainer.length > 0) //bids
				{
					var toggle = messageContainer.prev();
					if($("#divBids").css("display") == "none")
						toggleFoldout($("#divBids"), $("#divBidsToggle"));
					
					toggleFoldout(messageContainer, toggle);
				}
				else if(messageThread.length > 0)
				{
					var toggle = messageThread.prev();
					if($("#divMessages").css("display") == "none")
						toggleFoldout($("#divMessages"), $("#divMessagesToggle"));
					
					toggleFoldout(messageThread, toggle);
				}
			}
			
			location.hash = full;
		}
	}
});

$(window).unload( function () { GUnload(); } );