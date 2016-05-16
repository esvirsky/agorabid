<?=$this->load->view('header')?>

<div id="divHome">

	<div id="divAbout">
		<div id="divAboutWelcome">Welcome to <?= $this->config->item("site_domain")?></div>
		<div id="divAboutInfo">The site that makes it easy to order services - <a href="/general/about">find out how</a></div>
	</div>
	
	<img src="/images/home.jpg" alt="header image"/>
	
	<div id="divNewSR">
		<div class="InfoBox">
			<div id="divNewSRHeader" class="InfoBoxHeader">
				<div class="InfoBoxHeaderText">Buyers</div>
				<hr/>
			</div>
			<div id="divNewSRContent" class="InfoBoxContent">	
				<ol>
					<li>Create a service request</li>
					<li>Accept the best bid</li>
					<li>Get service</li>
					<li>Review the bidder</li>
				</ol>
				<div class="ViewTutorial"><a href="/general/tutorial#buyer">view buyer tutorial</a></div>
			</div>
		</div>
		<div id="divNewSRBigButton" class="BigButton"><a href="/service_request/create" rel="nofollow"><img src="/images/big_button_create.png" alt="Create" /></a></div>	
	</div>
	
	<div id="divSearchSR">
		<div class="InfoBox">
			<div id="divSearchSRHeader" class="InfoBoxHeader">
				<div class="InfoBoxHeaderText">Sellers</div>
				<hr/>
			</div>
			<div id="divSearchSRContent" class="InfoBoxContent">	
				<ol>
					<li>Find a service request</li>
					<li>Bid on it</li>
					<li>Negotiate the details</li>
					<li>Complete the service</li>
				</ol>
				<div class="ViewTutorial"><a href="/general/tutorial#seller">view seller tutorial</a></div>
			</div>
		</div>
		<div id="divSearchSRBigButton" class="BigButton"><a href="/service_request/search" rel="nofollow"><img src="/images/big_button_search.png" alt="Search" /></a></div>
	</div>
</div>

<?=$this->load->view('footer')?>
