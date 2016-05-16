<?=$this->load->view('header')?>

<?php 

$categories = $this->categorylib->getCategoryTree();

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

function _getPageUrl($page)
{
	$url = preg_replace('/&page=\d+/', "", $_SERVER["REQUEST_URI"]);
	return $url . "&page=" . $page;
}

?>

<div id="divSRSearch">
	<div id="divSearchForm" class="SectionBox">
		<form id="formSearch" name="formSearch" method="GET" action="/service_request/search">
		
			<table cellspacing="0" cellpadding="0">
				<tr class="HeaderRow">
					<td width="400"><label for="category">Category</label></td>
					<td><label for="address">Address</label></td>
					<td><label for="radius">Radius</label></td>
					<td></td>
				</tr>
				<tr>
					<td><input type="text" id="category" name="category" class="required" /></td>
					<td>
						<input type="text" id="address" name="address" value="<?=$this->input->get('address')?>" />
					</td>
					<td><input type="text" id="radius" name="radius"  value="<?=$this->input->get('radius') ? $this->input->get('radius') : "20"?>" /> miles</td>
					<td><button id="btnSearch" name="btnSearch">Search</button></td>
				</tr>
				<tr>
					<td id="tdEverywhere" colspan="8">
						<input type="checkbox" id="everywhere" name="everywhere" <?=$this->input->get("everywhere") ? "CHECKED" : ""?>/>
						<label id="lblEverywhere" for="everywhere">Search Everywhere</label>
					</td>
				</tr>
			</table>
			
			<ul id="ulCategoryTree" class="mcdropdown_menu">
				<?php foreach($categories as $category) { _printCategoryNode($category); } ?>
				<li rel='-1'>
					Select a Category
				</li>
			</ul>
		</form>
	</div>

	<?php if(!empty($serviceRequests)) {?>
		<div id="divSearchResults">
			
			<div id="divResultsNavigation1" class="ClearAfter">
				<?php if($page > 1) { ?>
					<div class="ResultsPrev">
						<a href="<?=_getPageUrl($page-1)?>">Prev Page</a>
					</div>
				<?php } ?>
				<?php if($next) { ?>
					<div class="ResultsNext">
						<a href="<?=_getPageUrl($page+1)?>">Next Page</a>
					</div>
				<?php } ?>
			</div>
		
			<div id="divSearchResultsList">
				<?php foreach($serviceRequests as $key => $serviceRequest) { ?>
					<a id="a<?=$key?>"></a>
					<div id="divServiceRequest<?=$serviceRequest->id?>" class="SectionBox InfoForm">
						<table cellspacing="0" cellpadding="0">
							<tr>
								<td width="50"><label>Title: </label></td><td width="165"><a href="/service_request/details/<?=$serviceRequest->id?>"><?=$serviceRequest->title?></a></td>
								<td width="60"><label>Created: </label></td><td class="Date" width="120"><?=formatDateForView(strtotime($serviceRequest->created))?></td>
								<td width="50"><label>Marker: </label></td><td><?=chr($key + 1 + 64)?></td>
							</tr>
							<tr><td><label>Category: </label></td><td colspan="5"><?=$serviceRequest->category->name?></td></tr>
							<tr><td><label>Description: </label></td><td colspan="5" class="DescriptionText"><?=nl2br(substr($serviceRequest->description, 0, 300)) . (strlen($serviceRequest->description) > 300 ? "...." : "")?></td></tr>
							<tr class="AddressRow">
								<td><label>Address: </label></td>
								<td colspan="2"><?=$serviceRequest->location->city?>, <?=$serviceRequest->location->region?> <?=$serviceRequest->location->postalCode?></td>
								<td colspan="3">
									<a href="/service_request/details/<?=$serviceRequest->id?>">View Details</a>
									<button id="btnShowOnMap<?=$key?>" class="ShowOnMapButton" <?=$serviceRequest->location->hasLatLong() ? "" : "disabled='true'"?>>Show on Map</button>
								</td>
							</tr>	
						</table>
						
					</div>
				<?php } ?>
				
				<div>
					<?php if($page > 1) { ?>
						<div class="ResultsPrev">
							<a href="<?=_getPageUrl($page-1)?>">Prev Page</a>
						</div>
					<?php } ?>
					<?php if($next) { ?>
						<div class="ResultsNext">
							<a href="<?=_getPageUrl($page+1)?>">Next Page</a>
						</div>
					<?php } ?>
				</div>
			</div>
			<div id="divMap">
				<div id="divMapContainer"></div>
				<div id='divMapNote'><b>Note:</b> Locations on map are only <br/>approximations. <a href='/user/privacy'>User Privacy</a></div>
			</div>
		</div>
	<?php } else if (isset($serviceRequests)) { ?>
		<div id="divSearchResultsNotFound" class="SectionBox">
			No Results Found
		</div>
	<?php } ?>
	
</div>
 
<?php if(!empty($serviceRequests)) {?> 
	<script type="text/javascript" src="http://maps.google.com/maps?file=api&v=2&key=<?=$this->config->item("google_key")?>"></script> 
<?php } ?>
<?php $this->carabiner->css("third_party/jquery.mcdropdown.css"); ?>
<?php $this->carabiner->js("third_party/jquery.mcdropdown.min.js"); ?>
<?php $this->carabiner->js("third_party/jquery.bgiframe.js"); ?>
<?php $this->carabiner->js("map.js"); ?>
<?php $this->carabiner->js("service_request/search.js"); ?>

<?=$this->load->view('footer')?>

<script>
<!--
	$(document).ready(function(){

		<?php if(!empty($serviceRequests)) {
			
			$points = array();
			foreach($serviceRequests as $serviceRequest)
			{
				$point = new GenericEntity();
				$point->latitude = $serviceRequest->location->latitude + $serviceRequest->location->offsetLatitude;
				$point->longitude = $serviceRequest->location->longitude + $serviceRequest->location->offsetLongitude;
				$points[] = $point;
			}
		?>
			$.Map = new Map($.evalJSON(<?='"'.addslashes(json_encode($points)).'"'?>), document.getElementById("divMapContainer"));
			$.Map.onMarkerClick = function(id){ window.scroll(0, document.getElementById('a' + id).offsetTop); };
			
		<?php } ?>


		if($.browser.safari)
			setTimeout(setCategory, 100);
		else
			setCategory();
		
		function setCategory()
		{
			$("#category").mcDropdown("#ulCategoryTree", { delim: " > ", allowParentSelect: true }); 
			var mcDropdown = $("#category").mcDropdown(); 
			mcDropdown.setValue(-1);
			<?php if($this->input->get("category") != false) { ?>
				mcDropdown.setValue(<?=$this->input->get("category")?>);
			<?php } ?>
		}
	});
//-->
</script>