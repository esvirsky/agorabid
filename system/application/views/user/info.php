<?=$this->load->view('header')?>  

<?php $user = $this->userlib->getLoggedInUser(); ?>

<?php if(!isset($user->info) || !$user->info->infoComplete) { ?>
	<p class="SectionBox">Please fill out this information form to help us customize the site to your needs</p>
<?php } ?>

<div id="divUserInfo">
	<form id="frmInfo" name="frmInfo" action="/user/info" method="POST"/>	
		<div class="Header">User information</div>
		<div class="SectionBox">
			<p class="PrivacyNotice"><b>Privacy Notice:</b> Buyers, when submitting a service request, your personal information (email, name, and phone number) will not
			be displayed to other users. It will only be shown after you have accepted a bid, and only to the bidder that you accepted. Look
			at our <a href="/user/privacy">user privacy page</a> for more information.</p>
			
			<table id="tblUserType" cellspacing="0" cellpadding="0">
				<tr>
					<td><label id="lblUserType">User Type<span class="RequiredStar">*</span></label></td>
					<td id="tdUserTypeInputGroup">
						<input id="rdbUserType1" name="rdbUserType" class="required" value="buyer" type="radio" <?= isset($user->info->userType) && $user->info->userType == "buyer" ? "checked" : "" ?> /><label for="rdbUserType1">Buyer</label>
						<input id="rdbUserType2" name="rdbUserType" class="required" value="seller" type="radio" <?= isset($user->info->userType) && $user->info->userType == "seller" ? "checked" : "" ?> /><label for="rdbUserType2">Seller</label>
						<input id="rdbUserType3" name="rdbUserType" class="required" value="seller" type="radio" /><label for="rdbUserType3">Both</label>
					</td>
				</tr>
			</table>
			
			<div id="divBuyerInfo">
				<table cellspacing="0" cellpadding="0">
					<tr><td><label for="txtFirstName">First Name</label></td><td><input id="txtFirstName" name="txtFirstName" type="text" <?=isset($user->info->firstName) ? "value='" . $user->info->firstName . "'" : ""?> /></td></tr>
					<tr><td><label for="txtLastName">Last Name</label></td><td><input id="txtLastName" name="txtLastName" type="text" <?=isset($user->info->lastName) ? "value='" . $user->info->lastName . "'" : ""?> /></td></tr>
					<tr><td><label for="txtBuyerPhone">Phone Number</label></td><td><input id="txtBuyerPhone" name="txtBuyerPhone" class="phone" type="text" <?=isset($user->info->phone) ? "value='" . $user->info->phone . "'" : ""?> /></td></tr>
				</table>
			</div>
			
			<div id="divSellerInfo">
				<table cellspacing="0" cellpadding="0">
					<tr><td><label for="txtCompanyName">Name <span class="SubLabel">(Company/Full Name)</span><span class="RequiredStar">*</span></label></td><td><input id="txtCompanyName" name="txtCompanyName" type="text" class="required" <?=isset($user->info->companyName) ? "value='" . $user->info->companyName . "'" : ""?> /></td></tr>
					<tr><td><label for="txtWebsite">Website</label></td><td><input id="txtWebsite" name="txtWebsite" class="url" type="text" <?=isset($user->info->website) ? "value='" . $user->info->website . "'" : ""?> /></td></tr>
					<tr><td><label for="txtSellerPhone">Primary Phone</label></td><td><input id="txtSellerPhone" name="txtSellerPhone" class="phone" type="text" <?=isset($user->info->phone) ? "value='" . $user->info->phone . "'" : ""?> /></td></tr>
					<tr><td><label for="txtDescription" class="TextareaLabel">Description</label></td><td><textarea id="txtDescription" name="txtDescription"><?=isset($user->info->description) ? $user->info->description  : ""?></textarea></td></tr>	
				</table>
			</div>
		</div>
		<button id="btnSave" type="submit" class="SubmitButton">Save Info</button>
	</form>
</div>

<!-- <script type="text/javascript" src="/third_party/additional-methods.js"></script>  -->
<?php $this->carabiner->js("third_party/additional-methods.js"); ?>
<?php $this->carabiner->js("user/info.js"); ?>

<?=$this->load->view('footer')?>  