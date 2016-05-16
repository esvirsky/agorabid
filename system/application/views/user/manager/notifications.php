<?php 

$this->notificationlib->model->order_by("created", "DESC");
$newMessageNotifications = $this->notificationlib->getNotificationsByUser($this->userlib->getLoggedInUser(), NotificationLib::TYPE_NEW_MESSAGE, true);

$this->notificationlib->model->order_by("created", "DESC");
$newBidNotifications = $this->notificationlib->getNotificationsByUser($this->userlib->getLoggedInUser(), NotificationLib::TYPE_NEW_BID, true);

$this->notificationlib->model->order_by("created", "DESC");
$acceptedBidNotifications = $this->notificationlib->getNotificationsByUser($this->userlib->getLoggedInUser(), NotificationLib::TYPE_BID_ACCEPTED, true);

$total = count($newMessageNotifications) + count($newBidNotifications) + count($acceptedBidNotifications);
?>

<?php if(!empty($newMessageNotifications) || !empty($newBidNotifications) || !empty($acceptedBidNotifications)) { ?>
	<div id="divNotificationsToggle" class="Foldout"><a name="notifications"><img src="/images/collapsed.gif" /> <span>Notifications</span></a>&nbsp;&nbsp;<span class="Notification">( <?=$total?> new )</span></div>
	<div id="divNotifications" class="SectionBox">
	
		<?php if(!empty($newMessageNotifications)) { ?>
			<div id="divNotificationMessagesToggle" class="SubFoldout"><a name="message_notifications"><img src="/images/collapsed.gif" /> <span>New Messages</span></a></div>
			<div id="divNotificationMessages">
				<table cellspacing="0" cellpadding="0" class="InfoTable">
					<tr><th>Date</th><th width="160">Service Request</th><th width="160">User</th><th>Message</th><th width="40">&nbsp;</th><th width="20">&nbsp;</th></tr>
					<?php foreach($newMessageNotifications as $key => $notification) { ?>
						<tr id="trNotification<?=$notification->id?>" class="<?=$key%2 == 1 ? "tr1" : "tr0"?>">
							<td class="Date"><?=formatDateForView(strtotime($notification->message->created))?></td>
							<td><a href="/service_request/details/<?=$notification->message->getServiceRequest()->id?>"><?=$notification->message->getServiceRequest()->title?></a></td>
							<td><a href="/user/details/<?=$notification->message->sender->username?>"><?=$notification->message->sender->username?></a></td>
							<td><?=nl2br(substr($notification->message->message, 0, 200)) . (strlen($notification->message->message) > 200 ? "..." : "")?></td>
							<td><a id="aViewNotification<?=$notification->id?>" class="ViewNotification" href="/service_request/details/<?=$notification->message->getServiceRequest()->id?>#message<?=$notification->message->id?>">View</a></td>
							<td><a id="aRemoveNotification<?=$notification->id?>" class="RemoveNotification"><img src="/images/delete.gif" alt="delete"/></a></td>
						</tr>
						
					<?php } ?>
				</table>
			</div>
		<?php } ?>

		<?php if(!empty($newBidNotifications)) { ?>
			<div id="divNotificationBidsToggle" class="SubFoldout"><a name="bid_notifications"><img src="/images/collapsed.gif" /> <span>New Bids</span></a></div>
			<div id="divNotificationBids">
				<table cellspacing="0" cellpadding="0" class="InfoTable">
					<tr><th>Date</th><th>Service Request</th><th width="110">User</th><th width="110">Price</th><th width="110">Time</th><th width="200">Description</th><th width="40">&nbsp;</th><th width="20">&nbsp;</th></tr>
					<?php foreach($newBidNotifications as $key => $notification) { ?>
						<tr id="trNotification<?=$notification->id?>" class="<?=$key%2 == 1 ? "tr1" : "tr0"?>">
							<td class="Date"><?=formatDateForView(strtotime($notification->bid->created))?></td>
							<td><a href="/service_request/details/<?=$notification->bid->serviceRequest->id?>"><?=$notification->bid->serviceRequest->title?></a></td>
							<td><a href="/user/details/<?=$notification->bid->user->username?>"><?=$notification->bid->user->username?></a></td>
							<td><?=$notification->bid->price?></td>
							<td><?=$notification->bid->time?></td>
							<td><?=nl2br(substr($notification->bid->note, 0, 100)) . (strlen($notification->bid->note) > 100 ? "...." : "")?></td>
							<td><a id="aViewNotification<?=$notification->id?>" class="ViewNotification" href="/service_request/details/<?=$notification->bid->serviceRequest->id?>#bid<?=$notification->bid->id?>">View</a></td>
							<td><a id="aRemoveNotification<?=$notification->id?>" class="RemoveNotification"><img src="/images/delete.gif" alt="delete"/></a></td>
						</tr>
						
					<?php } ?>
				</table>
			</div>	
		<?php } ?>
		
		<?php if(!empty($acceptedBidNotifications)) { ?>
			<div id="divNotificationAcceptedBidsToggle" class="SubFoldout"><a name="accepted_bid_notifications"><img src="/images/collapsed.gif" /> <span>Accepted Bids</span></a></div>
			<div id="divNotificationAcceptedBids">
				<table cellspacing="0" cellpadding="0" class="InfoTable">
					<tr><th>Date</th><th>Service Request</th><th width="75" title="Service Request Status">SR Status</th><th width="40">&nbsp;</th><th width="20">&nbsp;</th></tr>
					<?php foreach($acceptedBidNotifications as $key => $notification) { ?>
						<tr id="trNotification<?=$notification->id?>" class="<?=$key%2 == 1 ? "tr1" : "tr0"?>">
							<td class="Date"><?=formatDateForView(strtotime($notification->created))?></td>
							<td><a href="/service_request/details/<?=$notification->bid->serviceRequest->id?>"><?=$notification->bid->serviceRequest->title?></a></td>
							<td><?=$notification->bid->serviceRequest->status?></td>
							<td><a id="aViewNotification<?=$notification->id?>" class="ViewNotification" href="/service_request/details/<?=$notification->bid->serviceRequest->id?>#bid<?=$notification->bid->id?>">View</a></td>
							<td><a id="aRemoveNotification<?=$notification->id?>" class="RemoveNotification"><img src="/images/delete.gif" alt="delete"/></a></td>
						</tr>
						
					<?php } ?>
				</table>
			</div>	
		<?php } ?>
	
	</div>
<?php } ?>