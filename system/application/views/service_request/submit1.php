<?php 

/**
 * Recursively prints out a category node
 * 
 * @param $node
 * @return unknown_type
 */
function _printCategoryNode($node, $displayName = null)
{
	$name = $displayName != null ? $displayName : $node->name;

	echo "<li rel='$node->id'>\n";
	echo "$name\n";
	
	if(!empty($node->children))
		echo "	<ul>\n";
	
	foreach($node->children as $displayName => $child)
		_printCategoryNode($child, $displayName);
	
	if(!empty($node->children))
		echo "	</ul>\n";

	echo "</li>\n";	
}

?>

<div id="divSRSubmit1">
	
	<div class="SectionBox">
		<div class="SubHeader3">Please tell us what you want done</div>
		
		<input id="hdnDescription" type="hidden" />
		<input id="hdnCategory" type="hidden" />
		
		<table cellspacing="0" cellpadding="0">
			<tr><td><label for="txtTitle">Title<span class="RequiredStar">*</span></label></td><td><input id="txtTitle" name="txtTitle" class="required" type="text" value="<?=isset($serviceRequest) ? $serviceRequest->title : ""?>"/></td></tr>
			<tr><td><label for="txtCategory">Category<span class="RequiredStar">*</span></label></td><td><input type="text" id="txtCategory" name="txtCategory" class="required" value="<?=isset($serviceRequest) ? $serviceRequest->categoryId : ""?>"/></td></tr>
			<tr><td><label for="txtDescription" class="TextareaLabel">Description/Comments<span class="RequiredStar">*</span></label></td><td><textarea id="txtDescription" name="txtDescription" class="required"><?=isset($serviceRequest) ? $serviceRequest->description : ""?></textarea></td></tr>		
		</table>
	</div>	
		
	<?php if(!isset($serviceRequest)) { ?><button id="btnNext" name="btnNext" type="submit" class="SubmitButton">Next</button> <?php } ?>
	<div class="Clear">&nbsp;</div>
	
	<ul id="ulCategoryTree" class="mcdropdown_menu">
		<?php foreach($this->categorylib->getCategoryTree() as $category) { _printCategoryNode($category); } ?>
		<li rel='-1'>
			Select a Category
		</li>
	</ul>
		
</div>

<?php $this->carabiner->css("third_party/jquery.mcdropdown.css"); ?>

<?php $this->carabiner->js("third_party/jquery.mcdropdown.min.js"); ?>
<?php $this->carabiner->js("third_party/jquery.bgiframe.js"); ?>

<script>
<!--
$(document).ready(function(){
	
	$("#frmServiceRequest").validate({
		onkeyup: false,
		errorPlacement: function(error, element)
		{	
			if(element.parent().attr("class") == "mcdropdown")
				error.prependTo( element.parent().parent().parent());
			else
				error.prependTo( element.parent());
		},
		rules:
		{
			txtCategory: { min: 1 }
		},
		messages:
		{
			txtCategory: { min: "This field is required." }
		}
	});

	if($("#hdnCategory").val() != "")
		$("#txtCategory").val($("#hdnCategory").val());

	if($("#hdnDescription").val() != "")
		$("#txtDescription").val($("#hdnDescription").val());
	
	if($.browser.safari)
		setTimeout(setCategory, 100);
	else
		setCategory();
	
	function setCategory()
	{
		var selectedCategoryId = <?=isset($selectedCategory) ? $selectedCategory->id : "null"?>;
		var val = $("#txtCategory").val();
		val = val == "" && selectedCategoryId != null ? selectedCategoryId : val;
		
		$("#txtCategory").mcDropdown("#ulCategoryTree", { delim: " > ", targetColumnSize : 1 });
		var mcDropdown = $("#txtCategory").mcDropdown(); 
		mcDropdown.setValue(val == "" ? -1 : val);
	}
});

$(window).unload(function(){

	$("#hdnCategory").val($("#txtCategory").val());
	$("#hdnDescription").val($("#txtDescription").val());
	
});

//-->
</script>