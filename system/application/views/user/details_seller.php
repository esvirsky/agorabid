<?php 

	$title = $user->username . (isset($user->info->companyName) ? " - " . $user->info->companyName : "");
	$description = "User details page for user " . $title;
	headerView($title, $description);
?>

<?php 

$locations = $this->locationlib->getLocationsByUser($user);
$reviews = $this->reviewlib->getReviewsByUser($user);
$categories = $this->categorylib->getCategoriesByUser($user);

function _printCategories($categories)
{
	$CI =& get_instance();
	foreach($categories as $category)
		echo $category->name . " :: ";
}

?>

<div id="divSellerDetails" class="UserDetails">
		<div id="divInfo">
			<div id="divUserMain" class="SectionBox">
				<table cellspacing="0" cellpadding="0">
					<tr><td><span id="spanUsername"><?=$user->username?></span></td><td id="tdRatingStars" width="100%"><a href="#reviews" class="RatingLink"><?=$this->load->view('snippets/rating.php', array("rating" => ReviewEntity::getAverageRating($reviews), "count" => count($reviews)))?></a></td></tr>
				</table>
			</div>
			
			<div class="InfoForm">
				<table cellspacing="0" cellpadding="0" class="SectionBox">
					<tr><td width="100"><label>Name: </label></td><td><?=isset($user->info->companyName) ? $user->info->companyName : ""?></td></tr>
					<tr><td><label>Email: </label></td><td><?=$user->email?></td></tr>
					<tr><td><label>Website: </label></td><td><?=isset($user->info->website) ? "<a href='{$user->info->website}'>{$user->info->website}</a>" : "" ?></td></tr>
					<tr><td><label>Primary Phone: </label></td><td><?=isset($user->info->phone) ? $user->info->phone : "" ?></td></tr>
					<tr><td><label>Categories: </label></td><td class="CategoryColumn"><?=_printCategories($categories)?></td></tr>
					<tr><td><label>Description: </label></td><td><?=isset($user->info->description) ? nl2br($user->info->description) : "" ?></td></tr>
				</table>
			</div>
		</div>
		<?php if(!empty($locations)) { ?>
			<div id="divLocationsToggle" class="Foldout"><a name="locations"><img src="/images/collapsed.gif" /> <span>Locations</span></a></div>
			<div id="divLocations" class="ClearAfter">
				<div id="divLocationList">
					<?php foreach(array_slice($locations, 0, 25) as $key => $location){ ?>
						<a name="location<?=$location->id?>"></a>
						<div id="divLocation<?=$key?>" class="SectionBox InfoForm">
							<table cellspacing="0" cellpadding="0">
							  	<tr>
									<td width="80px"><label>Location:</label></td><td width="120"><?=chr($key + 1 + 64)?></td>
									<td width="80px"><label>Primary? </label></td><td width="119"><?=$location->primary ? "yes" : "no"?></td>
								</tr>
								<tr>
									<td><label>Name: </label></td><td colspan="3"><?=isset($location->name) ? $location->name : ""?></td>
								</tr>
								<tr>
									<td><label>Address: </label></td>
									<td colspan="3"><a href="<?=$this->googlemapslib->getGoogleMapLink($location)?>" target="_blank">
											<?=$location->street1 . (isset($location->street2) ? " " . $location->street2 : "")?><br />
											<?=$location->city?>, <?=$location->region?> <?=$location->postalCode?>
										</a>
									</td>
								</tr>
	
								<tr class="LocationDetails LocationDetails<?=$key?>">
									<td><label>Email: </label></td><td colspan="3"><?=isset($location->email) ? "<a href='mailto:$location->email'>" . wordwrap($location->email, 45, "<br />", true) . "</a>" : ""?></td>
								</tr>
								<tr class="LocationDetails LocationDetails<?=$key?>">
									<td><label>Phone: </label></td><td><?=isset($location->phone) ? $location->phone : ""?></td>
									<td><label>Fax: </label></td><td><?=isset($location->fax) ? $location->fax : ""?></td>
								</tr>
								<tr class="LocationDetails LocationDetails<?=$key?>">
									<td><label>Website: </label></td><td colspan="3"><?php if(isset($location->website)) { ?><a href="<?=$location->website?>"><?=wordwrap($location->website, 45, "<br />", true)?></a><?php } ?></td>
								</tr>
								<tr class="LocationDetails LocationDetails<?=$key?>">
									<td><label>Description: </label></td><td colspan="3"><?=isset($location->description) ? nl2br($location->description) : ""?></td>
								</tr>
								<tr class="LocationControls"><td colspan="2"><div id="divLocationDetailsToggle<?=$key?>" class="LocationDetailsToggle SubFoldout"><a><img src="/images/collapsed.gif" /> <span>Details</span></a></div></td><td colspan="2"><button id="btnShowOnMap<?=$key?>" class="ShowOnMapButton" type="button" <?=$location->hasLatLong() ? "" : "disabled='true'"?>>Show on Map</button></td></tr>
							</table>
						</div>
					<?php }?>
				</div>
				
				<div id="mapLocations"></div>
			</div>
		<?php } ?>
	
		<?php if(!empty($reviews)) { ?>
		<div id="divReviewsToggle" class="Foldout"><a name="reviews"><img src="/images/collapsed.gif" /> <span>Reviews</span></a></div>
		<div id="divReviews">
			<?php foreach($reviews as $key => $review) { ?>
				<div class="SectionBox ClearAfter InfoForm">
					<table cellspacing="0" cellpadding="0">
						<tr><td><label>Overall:</label></td><td><?=$this->load->view('snippets/rating.php', array("rating" => $review->rating, "count" => null))?></td></tr>
						<tr><td><label>Quality:</label></td><td><?=$this->load->view('snippets/rating.php', array("rating" => $review->quality, "count" => null))?></td></tr>
						<tr><td><label>Speed:</label></td><td><?=$this->load->view('snippets/rating.php', array("rating" => $review->speed, "count" => null))?></td></tr>
						<tr><td><label>Friendliness:</label></td><td><?=$this->load->view('snippets/rating.php', array("rating" => $review->friendliness, "count" => null))?></td></tr>
						<tr><td><label>Reliability:</label></td><td><?=$this->load->view('snippets/rating.php', array("rating" => $review->reliability, "count" => null))?></td></tr>	
					</table>
					<div class="ReviewText"><div class="ReviewTitle"><?=$review->title?></div><?=nl2br($review->review)?></div>
				</div>
			<?php } ?>
		</div>
		<?php } ?>
	
</div>


<?php $this->carabiner->css("third_party/jquery.rating.css"); ?>

<script type="text/javascript" src="http://maps.google.com/maps?file=api&v=2&key=<?=$this->config->item("google_key")?>"></script>
<?php $this->carabiner->js("third_party/jquery.rating.pack.js"); ?>
<?php $this->carabiner->js("map.js"); ?>
<?php $this->carabiner->js("user/details_seller.js"); ?>

<script type="text/javascript">
<!--

	$(document).ready(function(){

		<?php if(!empty($locations)) { ?>
			$.Map = new Map($.evalJSON(<?='"'.addslashes(json_encode(filterObjects(array_slice($locations, 0, 25), array("latitude", "longitude")))).'"'?>), document.getElementById("mapLocations"));
			$.Map.onMarkerClick = highlightLocation;
		<?php } ?>
	});
	
//-->
</script>

<?=$this->load->view('footer')?>