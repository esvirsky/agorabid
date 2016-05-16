<?=$this->load->view('header')?>

<?php 

	$user = $this->userlib->getLoggedInUser();
	$locations = $this->locationlib->getLocationsByUser($user);
	
	$locationFilters = array("id", "primary", "name", "street1", "street2", "city", "postalCode", "region", "country", "phone", "fax", "email", "website", "description");
?>

<?php if($user->isSeller() && count($locations) < 2) { ?> <style> #btnRemoveLocation0 { display: none; } </style> <?php } ?>

<?php if(!$user->info->infoComplete) { ?>
	<p>Please fill out this information form to help us customize the site to your needs</p>
	<div id="divUserInfoBreadCrumbs" class="SectionBox">
		<a href="/user/info">Personal Info</a> -> <a href="/user/location_manager">Locations</a> -> <a href="#" onclick="return false;" class="Incomplete">Categories</a>
	</div>
<?php } ?>

<div class="Header">Location Manager</div>
<?php if($user->isSeller()) { ?><p>Sellers have to have at least one location</p><?php } ?>
<form id="frmLocationManager" name="frmLocationManager" action="/user/location_manager" method="POST">
	<div id="divFormError" class="FormError" style="display: <?= isset($formError) ? "" : "none" ?>;"><?=isset($formError) ? $formError : ""?></div>

	<div id="divLocationManager">
	</div>
	
	<button id="btnAddLocation" type="button">Add Another Location</button>
	<button id="btnSubmit" type="submit" class="SubmitButton">Save Locations</button>
	
	<input type="hidden" name="hdnLastLocationId" id="hdnLastLocationId" value="0" />
	<input type="hidden" name="hdnLocations" id="hdnLocations" />
</form>	

<div id="divLocationTemplate">
	<div id="divLocation{NUMBER}" class="SectionBox">
		<table cellspacing="0" cellpadding="0">
			
			<tr><td colspan="4"><label for="chkPrimary{NUMBER}" class="PrimaryLocationLabel">Primary location</label><input id="chkPrimary{NUMBER}" name="chkPrimary{NUMBER}" class="primaryCheckbox" type="checkbox" /></td></tr>
			
			<tr class="HeaderRow">
				<td><label for="txtLocationName{NUMBER}">Location Name</label></td>
				<td><label for="txtLocationPhone{NUMBER}">Phone Number</label></td>
				<td><label for="txtStreet1{NUMBER}">Street<span class="RequiredStar">*</span></label></td>
				<td><label for="txtStreet2{NUMBER}">Street2</label></td>
			</tr>
			<tr>
				<td><input id="txtLocationName{NUMBER}" name="txtLocationName{NUMBER}" type="text" /></td>
				<td><input id="txtLocationPhone{NUMBER}" name="txtLocationPhone{NUMBER}" type="text" class="phone" /></td>
				<td><input id="txtStreet1{NUMBER}" name="txtStreet1{NUMBER}" type="text" class="required" /></td>
				<td><input id="txtStreet2{NUMBER}" name="txtStreet2{NUMBER}" type="text"/></td>
			</tr>
			
			<tr class="HeaderRow">
				<td><label for="txtCity{NUMBER}">City<span class="RequiredStar">*</span></label></td>
				<td><label for="ddnRegion{NUMBER}">State<span class="RequiredStar">*</span></label></td>
				<td><label for="txtPostalCode{NUMBER}">Postal Code<span class="RequiredStar">*</span></label></td>
				<td><label for="ddnCountry">Country<span class="RequiredStar">*</span></label></td>
			</tr>
			<tr>
				<td><input id="txtCity{NUMBER}" name="txtCity{NUMBER}" type="text" class="required"/></td>
				<td><?=$this->load->view('snippets/state_drop_down.php', array("required" => true))?></td>
				<td><input id="txtPostalCode{NUMBER}" name="txtPostalCode{NUMBER}" type="text" class="required digits"/></td>
				<td><?=$this->load->view('snippets/country_drop_down.php', array("required" => true))?></td>
			</tr>
			
			<?php if($user->isSeller()) { ?>
				<tr>
					<td colspan="4">These are optional fields, they are only needed if they differ from the primary info</td>
				</tr>
				<tr class="HeaderRow">
					<td><label for="txtLocationEmail{NUMBER}">Email</label></td>
					<td><label for="txtLocationWebsite{NUMBER}">Website</label></td>
					<td><label for="txtLocationFax{NUMBER}">Fax Number</label></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><input id="txtLocationEmail{NUMBER}" name="txtLocationEmail{NUMBER}" type="text" class="email" /></td>
					<td><input id="txtLocationWebsite{NUMBER}" name="txtLocationWebsite{NUMBER}" type="text" class="url" /></td>
					<td><input id="txtLocationFax{NUMBER}" name="txtLocationFax{NUMBER}" type="text" class="phone" /></td>
					<td>&nbsp;</td>
				</tr>
				
				<tr class="HeaderRow">
					<td colspan="4"><label for="txtLocationDescription{NUMBER}">Description</label></td>
				</tr>
				<tr>
					<td colspan="4"><textarea id="txtLocationDescription{NUMBER}" name="txtLocationDescription{NUMBER}"></textarea></td>
				</tr>
			<?php } ?>
			
			<tr><td colspan="4"><button id="btnRemoveLocation{NUMBER}" class="RemoveLocationButton" type="button">Remove Location</button></td></tr>
		</table>
		
		<input type="hidden" id="hdnLocationId{NUMBER}" name="hdnLocationId{NUMBER}" />
	</div>
</div>

<?php $this->carabiner->js("third_party/additional-methods.js"); ?>
<?php $this->carabiner->js("user/location_manager.js"); ?>


<script type="text/javascript">
<!--

	$(document).ready(function(){

		$.locationManager = new LocationManager($.evalJSON(<?='"'.addslashes(json_encode(filterObjects($locations, $locationFilters))).'"'?>), <?=$user->isSeller() ? "true" : "false" ?>);
		<?php if($user->isSeller() && empty($locations)) { ?> $.locationManager.addLocation(); <?php } ?>
	});
	
//-->
</script>

<?=$this->load->view('footer')?>