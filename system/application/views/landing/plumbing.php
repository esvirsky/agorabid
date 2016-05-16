<?=headerView($location == null ? "Plumbing" : "Plumbing in $location", "plumbing services and service providers" . ($location == null ? "" : " in $location"));?>

<div id="divLandingPage">

	<div class="SectionBox Section AdLanding">
		
		<img src="/images/home.jpg" alt="header image" class="FriendlyImage"/>
		
		<div class="BigAdText">Easiest Way to Find a Good Plumber!</div>
		
		<div class="MediumAdText">
			Get your free quotes - <a href="/service_request/create?categoryId=104" rel="nofollow">Tell us what you need done</a>
		</div>
		
		<div class="SubHeader2">This is how it works:</div>
		<ol class="DescriptionText">
			<li>You fill out a short form that describes your problem</li>
			<li>We contact lots of <?= $location == null ? "local" : $location?> plumbers with your request</li>
			<li>The plumbers bid on your request</li>
			<li>You choose the plumber with the best price and the best rating</li>
		</ol>
		
		<div class="SubHeader2">Why use our site?</div>
		<ul class="DescriptionText">
			<li>Don't spend your time calling every plumber to get a quote. With our system you tell us what you need done just once and we
			disseminate that information</li>
			<li>On our site, plumbers will have to compete with each other for the best bid; they'll be forced to lower their prices to compete</li>
			<li>Enjoy reviews and ratings from other users</li>
			<li><b>It's absolutely free</b></li>
		</ul>
		
		<div class="SubHeader2">Privacy</div>
		<div class="DescriptionText">
			We will not give out your private information to any of the plumbers. We will just point them to a link on our site that has the information
			that you put in - your service request. That way they will not be able to call you, email you, or even know who you are.
		</div>
		
		<br/>
		<div>
			- Want more information on how the process works? Read our short <a href="/general/tutorial">tutorial</a>.<br/>
			- Want to know more about what our site is for? Read our <a href="/general/about">about us page</a>.
		</div>
		
		<br/>
		<div class="LandingFindToday"><a href="/service_request/create?categoryId=104" rel="nofollow">Fill out a short request form</a></div>
	</div>

</div>

<?=$this->load->view('footer')?>