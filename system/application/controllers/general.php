<?php

/**
 * This controller is responsible for general site pages - home, contacts, about, ....
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class General extends Controller 
{
	/**
	 * Constructor
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		parent::Controller();	
	}
	
	/**
	 * Default page - home page
	 * 
	 * @return unknown_type
	 */
	public function index()
	{
		gotoView("general/home", "Home");
	}

	/**
	 * About us page
	 * 
	 * @return unknown_type
	 */
	public function about()
	{
		gotoView("general/about", "About Us");
	}
	
	/**
	 * Shows an article
	 * 
	 * @return unknown_type
	 */
	public function article($id)
	{
		if($id == null)
			return messageView("Error", $this->lang->line("invalid_request"));
			
		$this->load->library("articlelib");
		$article = $this->articlelib->getArticleById($id);
		if($article == null)
			return messageView("Error", $this->lang->line("invalid_request"));
			
		gotoView("general/article", null, array("article" => $article));
	}
	
	/**
	 * Lists all the categories and landing pages
	 * 
	 * @return unknown_type
	 */
	public function directory()
	{
		//------------------------------------------------------------------------------
		// Find landing pages
		//------------------------------------------------------------------------------
		$this->load->library("categorylib");
		$this->load->library("landingpagelib");
		gotoView("general/directory", "Browse Site");
	}
	
	/**
	 * Contact us page
	 * 
	 * @return unknown_type
	 */
	public function contact()
	{
		gotoView("general/contact", "Contact Us");
	}
	
	/**
	 * Landing pages and site directories
	 * 
	 * NOTE: params $name and $location are temporary for adwords. Eventually I should have
	 * actual landing pages for every location
	 * 
	 * @param $id Landing page id
	 * @return unknown_type
	 */
	public function landing($id, $name = null, $location = null)
	{
		$this->load->library("categorylib");
		$this->load->library("servicerequestlib");
		$this->load->library("landingpagelib");
		
		$landingPage = $this->landingpagelib->getLandingPageById($id);
		if($landingPage == null)
			return messageView("Error", $this->lang->line("invalid_request"));
			
		$categories = $landingPage->category->getChildren();
		$categories[] = $landingPage->category;
		
		$this->servicerequestlib->model->order_by("created", "DESC");
		$this->userlib->model->where("info.userType", "seller");
		$this->userlib->model->order_by("username", "ASC");
		
		if(isset($landingPage->latLongBox))
		{
			$sellers = $this->userlib->findUsers($landingPage->category, json_decode($landingPage->latLongBox));
			$serviceRequests = $this->servicerequestlib->findServiceRequestsBox($categories, json_decode($landingPage->latLongBox));
		}
		else
		{
			$sellers = $this->userlib->getUsersByCategory($landingPage->category);
			$serviceRequests = $this->servicerequestlib->getServiceRequestsByCategory($landingPage->category);
		}
		
		$viewPath = "landing/" . $landingPage->urlName;
		$viewFilePath = APPPATH . "views/" . $viewPath . ".php";
		if(file_exists($viewFilePath))
			return gotoView($viewPath, null, array("landingPage" => $landingPage, "sellers" => $sellers, "serviceRequests" => $serviceRequests, "location" => $location));
		else
			return gotoView("landing/general/category", null, array("category" => $landingPage->category, "sellers" => $sellers, "serviceRequests" => $serviceRequests, "location" => $location));
	}
	
	/**
	 * Privacy policy page
	 * 
	 * @return unknown_type
	 */
	public function privacy()
	{
		gotoView("general/privacy", "Privacy Policy");
	}
	
	/**
	 * Terms of service page
	 * 
	 * @return unknown_type
	 */
	public function tos()
	{
		gotoView("general/tos", "Terms of Use");
	}
	
	/**
	 * Tutorial page
	 * 
	 * @return unknown_type
	 */
	public function tutorial()
	{
		gotoView("general/tutorial", "Tutorial");
	}
}