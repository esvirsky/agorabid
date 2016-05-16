<?php if(!empty($bids)) { ?>
	<div id="divBidsToggle" class="Foldout"><a name="bids"><img src="/images/collapsed.gif" /> <span>Bids</span></a></div>
	<div id="divBids">
		<?php foreach($bids as $key => $bid) { ?>
			<a name="bid<?=$bid->id?>"></a>
			<div id="divBid<?=$bid->id?>" class="SectionBox">
				<div class="InfoForm">
					<table cellspacing="0" cellpadding="0" class="tblBidInfo">
						<tr>
							<td width="35"><label>User: </label></td>
							<td width="307">
								<div class="BidCreatorUsername"><a href="/user/details/<?=$bid->user->username?>"><?=$bid->user->username?></a></div>
								<div class="BidCreatorRating"><a href="/user/details/<?=$bid->user->username?>#reviews" class="RatingLink"><?=$this->load->view('snippets/rating.php', array("rating" => $bid->user->getAvgRating(), "count" => count($bid->user->reviews)))?></a></div>
							</td>
							
							<td width="35"><label>Submitted: </label></td>
							<td width="307"><?=formatDateForView(strtotime($bid->created))?></td>
							
							<td class="tdStatus">
								<?php if($bid->accepted) { ?>
									<span class='AcceptedStatus'>Accepted</span>
								<?php } else if ($isCreator) { ?>
									<button id='btnAcceptBid<?=$bid->id?>' type='button' class='AcceptBidButton'>Accept Bid</button>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><label>Price: </label></td>
							<td><?php if(!empty($bid->price)) { ?> <?=$bid->price?> - <span class="Precision">(<?=ucfirst($bid->pricePrecision)?>)</span> <?php } ?></td>
							
							<td><label>Time: </label></td>
							<td colspan="2"><?php if(!empty($bid->time)) { ?> <?=$bid->time?> - <span class="Precision">(<?=ucfirst($bid->timePrecision)?>)</span> <?php } ?></td>
						</tr>
						<tr>
							<td><label>Note: </label></td>
							<td colspan="4"><?=nl2br($bid->note)?></td>
						</tr>
					</table>
				</div>
				
				<div id="divBidMessagesToggle<?=$bid->id?>" class="BidMessagesToggle MessagesToggle SubFoldout">
					<a><img src="/images/collapsed.gif" /> <span><?=isset($bid->messageThread) ? "Messages" : "Send Message"?></span></a>
				</div>
				<div id="divBidMessageContainer<?=$bid->id?>" class="BidMessageContainer">		
				<?php if(isset($bid->messageThread)) { ?>
						<?=$this->load->view('service_request/details/messageThread', array("messageThread" => $bid->messageThread, "serviceRequest" => $bid->serviceRequest))?>
				<?php } else { ?>
						<?=$this->load->view('service_request/details/create_message', array("serviceRequest" => $serviceRequest, "type" => "bid", "id" => $bid->id))?>
				<?php } ?>
				</div>
			
			</div>
		<?php } ?>
	</div>
<?php } ?>	