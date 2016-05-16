<?=$this->load->view('header')?>  

<div id="divUserCreateReview" class="SectionBox">
	<div class="Header">Leave Feedback</div>
	<p>Leave feedback for <b><?=$bid->user->username?></b></p>
	<form id="formReview" name="formReview" action="<?=uri_string()?>" method="POST">
		<table cellspacing="0" cellpadding="0">
			<tr><td><label id="lblRating">Overall Rating<span class="RequiredStar">*</span></label></td><td><?=$this->load->view('snippets/rating_selector', array("name" => "rating", "required" => true))?></td></tr>
			<tr><td><label id="lblQuality">Quality</label></td><td><?=$this->load->view('snippets/rating_selector', array("name" => "quality", "required" => false))?></td></tr>
			<tr><td><label id="lblSpeed">Speed</label></td><td><?=$this->load->view('snippets/rating_selector', array("name" => "speed", "required" => false))?></td></tr>
			<tr><td><label id="lblFriendliness">Friendliness</label></td><td><?=$this->load->view('snippets/rating_selector', array("name" => "friendliness", "required" => false))?></td></tr>
			<tr><td><label id="lblReliability">Reliability <img src="/images/info_icon.png" alt="info" class="InfoImage" title="does the seller do what he says? when he says? and is he punctual?"/></label></td><td><?=$this->load->view('snippets/rating_selector', array("name" => "reliability", "required" => false))?></td></tr>
			<tr><td><label id="lblTitle" for="txtTitle">Title</label></td><td><input id="txtTitle" name="txtTitle" type="text" /></td></tr>
			<tr><td><label id="lblReview" for="txtReview" class="TextareaLabel">Review</label></td><td><textarea id="txtReview" name="txtReview"></textarea></td></tr>
			<tr><td colspan="2"><button id="btnSubmit" type="submit">Submit</button></td></tr>
		</table>
	</form>
</div>

<?php $this->carabiner->css("third_party/jquery.rating.css"); ?>

<?php $this->carabiner->js("third_party/jquery.rating.pack.js"); ?>
	
<script>
<!--
$(document).ready(function(){
		
		$("#formReview").validate({
			onkeyup: false,
			ignoreTitle: true,
			errorPlacement: function(error, element)
			{	
			     error.prependTo( element.parent());
			}
		});
});
//-->
</script>	
	
<?=$this->load->view('footer')?>  