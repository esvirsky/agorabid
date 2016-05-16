<div id="divSRSubmit2">

		<div class="SectionBox">
			<div class="SubHeader3">Please tell us where you want it done</div>
		
			<table id="tblLocationWhere" cellspacing="0" cellpadding="0">
				<tr>
					<td><label for="rdbSRLocation">Where will the service be performed?<span class="RequiredStar">*</span></label></td>
					<td>
						<input id="rdbSRLocationMine" name="rdbSRLocation" class="required" type="radio" value="mine"/> At my location &nbsp; 
						<input id="rdbSRLocationTheir" name="rdbSRLocation" class="required" type="radio" value="theirs"/> At sellers location
					</td>
				</tr>
			</table>
		
			<div id="divLocationAddress">
				<p id="pLocationAddress"></p>
				
				<table id="tblLocationAddress" cellspacing="0" cellpadding="0">
					<tr><td><label for="txtStreet1">Street</label></td><td><input id="txtStreet1" name="txtStreet1" type="text" value="<?=isset($serviceRequest) ? $serviceRequest->location->street1 : ""?>"/></td></tr>
					<tr><td><label for="txtStreet2">Street 2</label></td><td><input id="txtStreet2" name="txtStreet2" type="text" value="<?=isset($serviceRequest) ? $serviceRequest->location->street2 : ""?>"/></td></tr>
					<tr><td><label for="txtCity">City<span class="RequiredStar">*</span></label></td><td><input id="txtCity" name="txtCity" type="text" class="required" value="<?=isset($serviceRequest) ? $serviceRequest->location->city : ""?>"/></td></tr>
					<tr><td><label for="ddnState">State<span class="RequiredStar">*</span></label></td><td><?=$this->load->view('snippets/state_drop_down.php', array("required" => true))?></td></tr>
					<tr><td><label for="txtPostalCode">Postal Code<span class="RequiredStar">*</span></label></td><td><input id="txtPostalCode" name="txtPostalCode" type="text" class="required digits" value="<?=isset($serviceRequest) ? $serviceRequest->location->postalCode : ""?>"/></td></tr>
					<tr><td><label for="ddnCountry">Country<span class="RequiredStar">*</span></label></td><td><?=$this->load->view('snippets/country_drop_down.php', array("required" => true))?></td></tr>
				</table>
				
				<p id="pLocationAddressPrivacy"><b>Note:</b> The exact address will only be shown to the accepted bidder, everyone else will see an approximation. Look
					at our <a href="/user/privacy">user privacy page</a> for more information.</p>
				
				<?php if(!isset($serviceRequest)) { ?>
					<input id="txtTitle" name="txtTitle" type="hidden" value="<?=set_value("txtTitle")?>" />
					<input id="txtCategory" name="txtCategory" type="hidden" value="<?=set_value("txtCategory")?>" />
					<input id="txtDescription" name="txtDescription" type="hidden" value="<?=set_value("txtDescription")?>" />	
				<?php } ?>
				
			</div>
		</div>
		
		<button id="btnSubmit" name="btnSubmit" type="submit" class="SubmitButton"><?= isset($serviceRequest) ? "Save Request" : "Create Request" ?></button>
		
</div>

<?php $this->carabiner->js("third_party/additional-methods.js"); ?>
<?php $this->carabiner->js("service_request/submit2.js"); ?>

<script>
<!--
$(document).ready(function(){

	<?php if(isset($serviceRequest)) { ?>

		$("#ddnState").val("<?=$serviceRequest->location->region?>");
		$("#ddnCountry").val("<?=$serviceRequest->location->country?>");

		$("#rdbSRLocationMine").attr("checked", <?=$serviceRequest->atLocation ? "true" : "false"?>);
		$("#rdbSRLocationTheir").attr("checked", <?=$serviceRequest->atLocation ? "false" : "true"?>);
		
	<?php } else { ?>
		$("#ddnCountry").val("US");
	<?php } ?>
	
});

//-->
</script>