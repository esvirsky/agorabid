<?=$this->load->view('header')?>

<div id="divBuyerDetails" class="UserDetails InfoForm">
	<div id="divUserMain" class="SectionBox">
		<span id="spanUsername"><?=$user->username?></span>
	</div>
	
	<div class="SectionBox">
		<table cellspacing="0" cellpadding="0">
			<tr><td><label>User type: </label></td><td><?=$user->info->userType?></td></tr>
		</table>
	</div>
</div>

<?=$this->load->view('footer')?>