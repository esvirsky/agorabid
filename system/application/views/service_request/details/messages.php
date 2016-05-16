<?php if(!empty($messageThreads)) { ?>
	<div id="divMessagesToggle" class="Foldout"><a name="messages"><img src="/images/collapsed.gif" /> <span>Messages</span></a></div>
	<div id="divMessages">
		<?php foreach($messageThreads as $messageThread) { ?>
			<div class="SectionBox">
				<div id="divMessageThreadToggle<?=$messageThread->id?>" class="MessageThreadToggle MessagesToggle SubFoldout">
					<a><img src="/images/collapsed.gif" /> <span>Messages</span> - from <?=$messageThread->user->username?></a>
				</div>
				<?=$this->load->view('service_request/details/messageThread', array("messageThread" => $messageThread, "serviceRequest" => $serviceRequest))?>
			</div>
		<?php } ?>
	</div>
<?php } ?>