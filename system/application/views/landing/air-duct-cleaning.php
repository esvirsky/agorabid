<?=headerView("Air duct cleaning", "Air duct cleaning services and service providers");?>

<div id="divLandingPage">

	<div class="LandingFindToday"><a href="/service_request/create?categoryId=36" rel="nofollow">Find an air duct cleaning specialist!</a></div>

	<?php if(!empty($sellers)) { $this->load->view('landing/general/sellers', array("sellers" => $sellers)); } ?>
	<?php if(!empty($serviceRequests)) { $this->load->view('landing/general/serviceRequests', array("serviceRequests" => $serviceRequests)); } ?>
	
	<div id="divSpecificLandingPages" class="Section">
		<div class="Header">Cities</div>
		<div class="SectionBox">
			<ul>
				<li><a href="/landing/114/air-duct-cleaning/denver">Air duct cleaning in Denver, CO</a></li>
			</ul>
		</div>
	</div>
	
	<div id="divArticles" class="Section">
		<div class="Header">Articles</div>
		<div class="SectionBox">
			<ul>
				<li><a href="/article/1/inspecting-air-ducts">How to Inspect Your Air Ducts</a></li>
			</ul>
		</div>
	</div>
	
	<div class="LandingFindToday"><a href="/service_request/create?categoryId=36" rel="nofollow">Find an air duct cleaning specialist!</a></div>

</div>

<?=$this->load->view('footer')?>