<?php 

function _printLocations()
{
	$CI =& get_instance();
	$user = $CI->userlib->getLoggedInUser();
	echo "<option value=''>Please select the location that will perform the service...</option>";
	foreach($user->locations as $location)
		echo "<option value='{$location->id}'>" . (!empty($location->name) ? $location->name . " - " . $location->street1 : $location->street1) . ", " .
			$location->city . ", " . $location->region . " " . $location->postalCode . "</option>\n";
}

?>

<div id="divCreateBid" class="SectionBox">
	<div class="Header">Submit Bid</div>
	
	<form id="formBid" name="formBid" action="/service_request/create_bid/<?=$serviceRequest->id?>" method="POST"/>
		<div id="divFormError" class="FormError" style="display: <?= isset($formError) ? "" : "none" ?>;"><?=isset($formError) ? $formError : ""?></div>
		<table cellspacing="0" cellpadding="0">
			<tr>
				<td><label id="lblPrice">Price</label></td><td width="200"><input id="txtPrice" name="txtPrice" maxlength="255" /></td>
				<td class="PrecisionColumn">
					<label id="lblPricePrecision" class="PrecisionLabel">Price is<span class="RequiredStar">*</span></label>&nbsp;&nbsp;
					<input id="rdbPriceEstimate" name="rdbPricePrecision" type="radio" value="estimate" class="RadioBox"/><label id="lblPriceEstimate" class="PrecisionRadioLabel">an estimate</label>
					&nbsp;
					<input id="rdbPriceExact" name="rdbPricePrecision" type="radio" value="exact" class="RadioBox"/><label id="lblPriceExact" class="PrecisionRadioLabel">exact</label>
				</td>
			</tr>
			<tr>
				<td><label id="lblTime">Time</label></td><td><input id="txtTime" name="txtTime" maxlength="255" /></td>
				<td class="PrecisionColumn">
					<label id="lblTimePrecision" class="PrecisionLabel">Time is<span class="RequiredStar">*</span></label>&nbsp;&nbsp;
					<input id="rdbTimeEstimate" name="rdbTimePrecision" type="radio" value="estimate" class="RadioBox"/><label id="lblTimeEstimate" class="PrecisionRadioLabel">an estimate</label>
					&nbsp;
					<input id="rdbTimeExact" name="rdbTimePrecision" type="radio" value="exact" class="RadioBox"/><label id="lblTimeExact" class="PrecisionRadioLabel">exact</label>
				</td>
			</tr>
			<tr class="LocationsRow"><td><label id="lblLocation">Location<span class="RequiredStar">*</span> <img src="/images/info_icon.png" alt="info" class="InfoImage" title="This is the location that will perform the service"/></label></td><td colspan="2"><select id="listLocations" name="listLocations" class="required"><?=_printLocations()?></select></td></tr>
			<tr><td><label id="lblNote" class="TextareaLabel">Note</label></td><td colspan="2"><textarea id="txtNote" name="txtNote"></textarea></td></tr>
			<tr><td colspan="3"><button id="btnSubmitBid" name="btnSubmitBid">Submit Bid</button></td></tr>
		</table>
	</form>
</div>