<?=$this->load->view('header')?>

<div id="divGeneralConfirm" class="SectionBox">
	<p><?=$question?></p>
	
	<div id="divButtons">
		<button id="btnYes" name="btnYes" type="button">Yes</button> &nbsp;&nbsp;&nbsp; <button id="btnNo" name="btnNo" type="button">No</button>
	</div>
</div>

<?=$this->load->view('footer')?>

<script>
<!--

$(document).ready(function(){

	$("#btnYes").click(function(){ self.location = "<?=$url?>/yes"; } );
	$("#btnNo").click(function(){ self.location = "<?=$url?>/no"; } );
	
});

//-->
</script>