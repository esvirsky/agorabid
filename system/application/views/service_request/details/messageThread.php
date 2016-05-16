<?php 

$this->messagelib->messageModel->order_by("created", "ASC");
$messages = $this->messagelib->getMessagesByMessageThread($messageThread);

?>

<div id="divMessageThread<?=$messageThread->id?>" class="MessageThread">
	<table cellspacing="0" cellpadding="0" class="InfoTable">
		<tr><th width="116">Date</th><th width="160">User</th><th>Message</th></tr>
		<?php foreach($messages as $key => $message) { ?>
			<tr class="<?=$key%2 == 1 ? "tr1" : "tr0"?>">
				<td class="Date"><a name="message<?=$message->id?>"></a><?=formatDateForView(strtotime($message->created))?></td>
				<td><a href="/user/details/<?=$message->sender->username?>"><?=$message->sender->username?></a></td>
				<td><?=nl2br($message->message)?></td>
			</tr>
		<?php } ?>
	</table>
	
	<button id="btnMessageThreadReply<?=$messageThread->id?>" class="MessageThreadReplyButton">Reply</button>
	<div id="divCreateThreadMessage<?=$messageThread->id?>"	class="CreateThreadMessage">
		<?=$this->load->view('service_request/details/create_message', array("serviceRequest" => $serviceRequest, "type" => "message_thread", "id" => $messageThread->id))?>
	</div>
</div>