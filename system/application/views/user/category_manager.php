<?=$this->load->view('header')?>  

<?php

$user = $this->userlib->getLoggedInUser();
$categories = $this->categorylib->getCategoryTree();
$selectedCategories = $this->categorylib->getCategoriesByUser($user);

/**
 * Recursively prints out a category node
 * 
 * @param $node
 * @return unknown_type
 */
function _printCategoryNode($node, $displayName = null)
{
	$name = $displayName != null ? $displayName : $node->name;

	echo "<li>\n";
	echo "	<input type='checkbox' name='chkCategories[]' id='chkCategory$node->id' value='$node->id' />\n";
	echo "	<label>$name</label>\n";
	
	if(!empty($node->children))
		echo "	<ul>\n";
	
	foreach($node->children as $displayName => $child)
		_printCategoryNode($child, $displayName);
	
	if(!empty($node->children))
		echo "	</ul>\n";

	echo "</li>\n";	
}

?>

<?php if(!$user->info->infoComplete) { ?>
	<p>Please fill out this information form to help us customize the site to your needs</p>
	<div id="divUserInfoBreadCrumbs" class="SectionBox">
		<a href="/user/info">Personal Info</a> -> <a href="/user/location_manager">Locations</a> -> <a href="/user/category_manager">Categories</a>
	</div>
<?php } ?>	

<div id="divCategoryManager">
	<div class="Header">Seller Categories</div>
	<form id="frmCategoryManager" name="frmCategoryManager" action="/user/category_manager" method="POST">
		<div class="SectionBox">
			<p>Please select all categories that apply to you. Sellers must have at least one category.</p>
			<div id="divFormError" class="FormError" style="display: <?= isset($formError) ? "" : "none" ?>;"><?=isset($formError) ? $formError : ""?></div>
			
			<ul id="ulCategoryTree" class="ClearAfter">
				<?php foreach($categories as $category) { _printCategoryNode($category); } ?>
			</ul>
		</div>
		<button id="btnSave" type="submit" class="SubmitButton">Save Categories</button>
	</form>
</div>

<?php $this->carabiner->js("third_party/jquery.checktree_yctin.min.js"); ?>
<?php $this->carabiner->js("user/category_manager.js"); ?>

<?php $this->carabiner->display(); ?>

<script type="text/javascript">
<!--

	$(document).ready(function(){

		selectCategories($.evalJSON(<?='"'.addslashes(json_encode($selectedCategories)).'"'?>));
		
	});
	
//-->
</script>

<?=$this->load->view('footer', array("dontDisplayCarabiner" => true))?>