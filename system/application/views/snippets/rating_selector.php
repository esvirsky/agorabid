<?php 

$inputName = "rdb" . ucfirst($name);
$displayId = "span" . ucfirst($name);

?>

<input id="<?=$inputName?>1" name="<?=$inputName?>" value="1" type="radio" class="hover-star<?=$required ? " required" : ""?>" title="very bad" /> 
<input id="<?=$inputName?>2" name="<?=$inputName?>" value="2" type="radio" class="hover-star<?=$required ? " required" : ""?>" title="bad" /> 
<input id="<?=$inputName?>3" name="<?=$inputName?>" value="3" type="radio" class="hover-star<?=$required ? " required" : ""?>" title="average" />
<input id="<?=$inputName?>4" name="<?=$inputName?>" value="4" type="radio" class="hover-star<?=$required ? " required" : ""?>" title="good" />
<input id="<?=$inputName?>5" name="<?=$inputName?>" value="5" type="radio" class="hover-star<?=$required ? " required" : ""?>" title="very good" />&nbsp;

<span id="<?=$displayId?>"></span>

<?php $this->carabiner->js("rating_selector.js"); ?>

<script type="text/javascript">
<!--

	$(document).ready(function(){

		ratingSelector = new RatingSelector("<?=$inputName?>", $("#<?=$displayId?>"));
	});
	
//-->
</script>