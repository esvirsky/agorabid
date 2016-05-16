<?=$this->load->view('header')?>

<div id="divUserPrivacy">

	<div class="Header">User Privacy</div>
	<div class="SectionBox">
		<p>Your privacy is important to us. This page talks about how we protect specific user information. It is not the site's privacy
		policy. Look at the <a href="/general/privacy">privacy policy</a> to see how we collect and use your data.</p>
	
		<h2>Buyers</h2>
		<p>Users who choose to be user type "buyer" will not have their personal information (such as name, email, address, phone number) 
		publicly available. The only time that this information will be shown is after the buyer accepts a bidder, and only to the accepted bidder.</p>
		
		<p>Because sellers have to have a rough idea of where the buyer is located, 
		we create an approximate location which is a random small distance away from the buyer's actual location. 
		We then plot this approximate location on the map. We only show the city, state, and postal code on the service request details page. 
		When a bidder is accepted by the buyer, and if the service is to be performed at location, we will show the accepted bidder the exact address, 
		so that the accepted bidder knows where to go.</p>
		
		<h2>Sellers</h2>
		<p>Users who choose to be user type "seller" will have their personal information (such as company name, phone, fax, email, website, description, categories, locations, etc.) publicly available on their user details page. 
		We believe that a seller would want as much exposure as possible and wouldn't want to hide their personal information.</p>
	</div>
	
</div>

<?=$this->load->view('footer')?>