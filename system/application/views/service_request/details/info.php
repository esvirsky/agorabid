<div id="divInfo" class="InfoForm">	
	<div class="SectionBox">
		<table cellspacing="0" cellpadding="0">
			<tr><td><label>Title: </label></td><td id="tdTitle"><?=$serviceRequest->title?></td></tr>
			<tr><td><label>Description: </label></td><td><?=nl2br($serviceRequest->description)?></td></tr>
		</table>	
	</div>

	<div id="divLeftInfo">
		
		<div class="SubHeader2">Service Request Details</div>
		<div class="SectionBox">
			
			<table cellspacing="0" cellpadding="0">		
				<tr><td><label>Category: </label></td><td><?=$serviceRequest->category->name?></td></tr>
				<tr class="AddressRow">
					<td><label>Address: </label></td>
					<td colspan="3">
						<?php if($showExactAddress) { ?>
							<a href="<?=$this->googlemapslib->getGoogleMapLink($serviceRequest->location)?>" target="_blank">
								<?=$serviceRequest->location->street1 . (isset($serviceRequest->location->street2) ? " " . $serviceRequest->location->street2 : "")?><br />
								<?=$serviceRequest->location->city?>, <?=$serviceRequest->location->region?> <?=$serviceRequest->location->postalCode?>
							</a>
						<?php } else { ?>
							<?=$serviceRequest->location->city?>, <?=$serviceRequest->location->region?> <?=$serviceRequest->location->postalCode?>
						<?php } ?>
					</td>
				</tr>
				<tr><td><label>At location<img src="/images/info_icon.png" alt="info" class="InfoImage" title="Is the service to be performed at this location ( the buyer's location )?"/>: </label></td><td><?=$serviceRequest->atLocation ? "Yes" : "No"?></td></tr>
				<tr><td><label>Created: </label></td><td><?=formatDateForView(strtotime($serviceRequest->created))?></td></tr>
				<tr><td><label># Bids: </label></td><td><?=count($bids)?></td></tr>
				<tr><td><label>Status: </label></td><td><?=$serviceRequest->status == ServiceRequestLib::STATUS_OPEN ? "Open" : "<span class='SRStatus'>Closed</span>"?></td></tr>
			</table>
		</div>

		<div class="SubHeader2">Creator Info</div>
		<div class="SectionBox">
			<table cellspacing="0" cellpadding="0">
				<tr><td><label>Username: </label></td><td><a href="/user/details/<?=$serviceRequest->user->username?>"><?=$serviceRequest->user->username?></a></td></tr>
				
				<?php if($showEverything) { ?>
					<tr>
						<td><label>Name: </label></td>
						<td>
							<?php 
								if($serviceRequest->user->isBuyer()) { 
									echo (isset($serviceRequest->user->info->firstName) ? $serviceRequest->user->info->firstName : "") . " " . (isset($serviceRequest->user->info->lastName) ? $serviceRequest->user->info->lastName : ""); 
								} else { 
									echo isset($serviceRequest->user->info->companyName) ? $serviceRequest->user->info->companyName : "";
								}
							?>
						</td>
					</tr>
					<tr><td><label>Email: </label></td><td><a href="mailto:<?=$serviceRequest->user->email?>"><?=$serviceRequest->user->email?></a></td></tr>
					<tr><td><label>Phone: </label></td><td><?=isset($serviceRequest->user->info->phone) ? $serviceRequest->user->info->phone : ""?></td></tr>
				<?php } ?>
			</table>
		</div>
	</div>
	
	<div id="divMap">
		<div id="divMapContainer"></div>
		<?=$showEverything ? "" : "<div id='divMapNote'><b>Note:</b> Location on map is only an approximation. <a href='/user/privacy'>User Privacy</a></div>"?>
	</div>
	
	<div class="Clear">&nbsp;</div>
</div>



