<?=$this->load->view('header')?>

<div id="divGeneralTutorial">

	<div class="Header">Tutorial</div>
	<div class="SectionBox">
		<p><b>Note: </b>You will sometimes see this icon <img src="/images/info_icon.png" alt="info" class="InfoImage" title="Hover over me for more info"/>
		around the site. Hover your mouse over this icon to get extra information.</p>
	
		<ul>
			<li><a href="#buyer">Buyer tutorial</a></li>
			<li><a href="#seller">Seller tutorial</a></li>
		</ul>
	
		<a name="buyer"></a>
		<div class="SubHeader1">Buyer Tutorial</div>
		<div id="divBuyerTutorial">
			<ol>
				<li>
					Create a new service request<br/><br/>
					<img src="/images/tutorial_buyer_home.png" alt="home page"/><br/><br/>
					<img src="/images/tutorial_buyer_create_sr.png" alt="create sr"/><br/><br/>
				</li>
				<li>
					Wait for new bids to come in and accept the bid that you like the most<br/><br/>
					<img src="/images/tutorial_buyer_sr_details.png" alt="sr details"/><br/>
					<span class="NormalFontSpan">You can then negotiate on the date and time of service</span><br/><br/>
				</li>
				<li>
					After you have received your service you can come back and rate the seller<br/><br/>
					<img src="/images/tutorial_buyer_feedback.png" alt="feedback"/><br/><br/>
				</li>
			</ol>
		</div>
		
		<a name="seller"></a>
		<div class="SubHeader1">Seller Tutorial</div>
		<div id="divSellerTutorial">
			<ol>
				<li>
					Search for service requests in your area<br/><br/>
					<img src="/images/tutorial_seller_home.png" alt="home page"/><br/><br/>
					<img src="/images/tutorial_seller_search.png" alt="search srs"/><br/><br/>
				</li>
				<li>
					Bid on a service request that you would like to do<br/><br/>
					<img src="/images/tutorial_seller_create_bid.png" alt="create bid"/><br/><br/>
				</li>
				<li>
					If your bid is accepted, arrange for a date and time with the buyer, complete the service, and collect payment<br/><br/>
					<img src="/images/tutorial_seller_send_message1.png" alt="send message 1"/><br/><br/>
					<img src="/images/tutorial_seller_send_message2.png" alt="send message 2"/><br/><br/>
				</li>
			</ol>
		</div>
	</div>
</div>

<?=$this->load->view('footer')?>