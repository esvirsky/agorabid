<?php 

$suffix = $type . $id;
$user = $this->userlib->getLoggedInUser();

?>

<form id="formCreateMessage<?=$suffix?>" name="formCreateMessage<?=$suffix?>" action="/service_request/create_<?=$type?>_message/<?=$id?>" class="FormCreateMessage" method="POST">
	<table cellspacing="0" cellpadding="0">
		<tr><td><label class="TextareaLabel">Message </label></td><td><textarea id="txtMessage_<?=$suffix?>" name="txtMessage_<?=$suffix?>" class="required" ></textarea></td></tr>
		<?php if($user != null && $user->id == $serviceRequest->userId) { ?>
			<tr>
				<td><label>Make this message public<img src="/images/info_icon.png" alt="info" class="InfoImage" title="Appends the message to the service request description. Making it visible to everyone."/></label></td>
				<td>
					<input id="chkPublicMessage_<?=$suffix?>" name="chkPublicMessage_<?=$suffix?>" class="PublicMessageCheckbox" type="checkbox"/>
					<button id="btnSubmit<?=$suffix?>" name="btnSubmit<?=$suffix?>" type="submit">Send</button>
				</td>
			</tr>
		<?php } else { ?>
			<tr><td colspan="2"><button id="btnSubmit<?=$suffix?>" name="btnSubmit<?=$suffix?>" type="submit">Send</button></td></tr>
		<?php } ?>
	</table>
</form>