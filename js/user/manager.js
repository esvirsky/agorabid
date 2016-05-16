$(document).ready(function(){

	$(".ViewNotification").click(viewNotification);
	$(".RemoveNotification").click(removeNotification);
	
	createFoldout($("#divPendingReviews"), $("#divPendingReviewsToggle"));
	createFoldout($("#divNotifications"), $("#divNotificationsToggle"));
	createFoldout($("#divNotificationMessages"), $("#divNotificationMessagesToggle"));
	createFoldout($("#divNotificationBids"), $("#divNotificationBidsToggle"));
	createFoldout($("#divNotificationAcceptedBids"), $("#divNotificationAcceptedBidsToggle"));
	createFoldout($("#divServiceRequests"), $("#divServiceRequestsToggle"));
	createFoldout($("#divBids"), $("#divBidsToggle"));
	createFoldout($("#divInfoManager"), $("#divInfoManagerToggle"));
	
	function viewNotification()
	{
		var id = this.id.replace(/aViewNotification/, "");
		jQuery.get("/user/remove_notification/" + id, null, viewNotificationCallback);
		return false;
	}
	
	function viewNotificationCallback(notificationId)
	{
		self.location = $("#aViewNotification" + notificationId).attr("href");
	}
	
	function removeNotification()
	{
		if(!confirm("Are you sure you want to remove this notification?"))
			return;
		
		var id = this.id.replace(/aRemoveNotification/, "");
		jQuery.get("/user/remove_notification/" + id);
		$("#trNotification" + id).remove();
	}
});