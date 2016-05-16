<?php

/**
 * This controller is responisble for dealing with users. Anything
 * that is not auth specific.
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class User extends Controller
{
	/**
	 * Constructor - holds common functions for all controller interfaces
	 */
	public function __construct()
	{
		parent::Controller();
		$this->lang->load('user', 'english');
	}
		
	/**
	 * Creates a review for a bid
	 * 
	 * @param $bidId
	 * @return unknown_type
	 */
	public function create_bid_review($bidId = null)
	{
		$this->userlib->check(UserLib::LEVEL_BUYER);
		
		$this->load->library("bidlib");
		$this->load->library("reviewlib");
		
		if($bidId == null)
			return messageView("Error", $this->lang->line("invalid_request"));
		
		$user = $this->userlib->getLoggedInUser();
		$bid = $this->bidlib->getBidById($bidId);
		
		if($bid == null)
			return messageView("Error", $this->lang->line("invalid_request"));
	
		if($this->reviewlib->hasReviewedBid($user, $bid))
			return messageView("Already Reviewed", $this->lang->line("user_already_reviewed"));	
			
		if(!$this->reviewlib->canReviewBid($user, $bid))
			return messageView("Error", $this->lang->line("invalid_request"));	
						
		//------------------------------------------------------------------------------
		// Load Form
		//------------------------------------------------------------------------------
		if($this->input->post("txtTitle") === false)
			return gotoView("user/create_review", "Submit a Review", array("bid" => $bid));

		//------------------------------------------------------------------------------
		// Form validation
		//------------------------------------------------------------------------------
		$this->form_validation->set_rules("rdbRating", "Rating", "required|callback__validation_rating");
		$this->form_validation->set_rules("rdbFriendliness", "Friendliness", "callback__validation_rating");
		$this->form_validation->set_rules("rdbReliability", "Reliability", "callback__validation_rating");
		$this->form_validation->set_rules("rdbQuality", "Quality", "callback__validation_rating");
		$this->form_validation->set_rules("rdbSpeed", "Speed", "callback__validation_rating");
		if(!$this->form_validation->run())
			return gotoView("user/create_review", "Submit a Review", array("error" => $this->lang->line("invalid_request"), "bid" => $bid));

		//------------------------------------------------------------------------------
		// Create review
		//------------------------------------------------------------------------------
		$this->load->orm("ReviewModel");
		$reviewModel = $this->ReviewModel->new_record();
		$reviewModel->creatorId = $user->id;
		$reviewModel->bidId = $bid->id;
		$reviewModel->title = $this->input->xss_clean($this->input->post("txtTitle"));
		$reviewModel->rating = $this->input->post("rdbRating");
		$reviewModel->friendliness = $this->input->post("rdbFriendliness") == 0 ? null : $this->input->post("rdbFriendliness");
		$reviewModel->reliability = $this->input->post("rdbReliability") == 0 ? null : $this->input->post("rdbReliability");
		$reviewModel->quality = $this->input->post("rdbQuality") == 0 ? null : $this->input->post("rdbQuality");
		$reviewModel->speed = $this->input->post("rdbSpeed") == 0 ? null : $this->input->post("rdbSpeed");
		$reviewModel->review = $this->input->xss_clean($this->input->post("txtReview"));
		$reviewModel->save() or error();
		
		messageView("Review Submitted", $this->lang->line("user_created_review"));
	}
	
	/**
	 * Shows user details
	 * 
	 * @param $id the user id
	 * @return unknown_type
	 */
	public function details($username = null)
	{
		if($username == null)
			return messageView("Error", $this->lang->line("invalid_request"));
			
		$user = $this->userlib->getUserByUsername($username);
		if($user == null || $user->activated == false || !isset($user->info))
			return messageView("Error", $this->lang->line("invalid_request"));
		
		$this->load->library("locationlib");
		$this->load->library("reviewlib");
		$this->load->library("categorylib");
		$this->load->library("googlemapslib");
	
		gotoView($user->isBuyer() ? "user/details_buyer" : "user/details_seller", "User Details", array("user" => $user));
	}
	
	/**
	 * User info
	 */
	public function info()
	{
		$this->authlib->check(AuthLib::LEVEL_REGISTERED);

		//------------------------------------------------------------------------------
		// Load Form
		//------------------------------------------------------------------------------
		if($this->input->post("rdbUserType") === false)
			return gotoView("user/info", "Change Info");
		
		//------------------------------------------------------------------------------
		// Submit Form - Validation
		//------------------------------------------------------------------------------
		$this->form_validation->set_rules("rdbUserType", "User Type", "callback__validation_userType");
		if($this->input->post("rdbUserType") == "seller")
			$this->form_validation->set_rules("txtCompanyName", "Company Name", "required");
			
		if(!$this->form_validation->run())
			return gotoView("user/info", "Change Info", array("error" => $this->lang->line("invalid_request")));

		//------------------------------------------------------------------------------
		// Load Form Data
		//------------------------------------------------------------------------------
		$userType = $this->input->post("rdbUserType");
		if($userType == "buyer")
		{
			$firstName = $this->input->xss_clean($this->input->post("txtFirstName"));
			$lastName = $this->input->xss_clean($this->input->post("txtLastName"));
			$phone = $this->input->xss_clean($this->input->post("txtBuyerPhone"));
		}
		else
		{
			$companyName = $this->input->xss_clean($this->input->post("txtCompanyName"));
			$phone = $this->input->xss_clean($this->input->post("txtSellerPhone"));
			$website = $this->input->xss_clean($this->input->post("txtWebsite"));
			$description = $this->input->xss_clean($this->input->post("txtDescription"));
		}
		
		//------------------------------------------------------------------------------
		// Save info changes
		//------------------------------------------------------------------------------
		$user = $this->userlib->getLoggedInUser();
		$userInfo = isset($user->info) ? $this->UserInfoModel->find($user->id) : $this->UserInfoModel->new_record();
		$userInfo->userId = $user->id;
		$userInfo->userType = $userType;
		$userInfo->phone = $phone;
		
		if($userType == "buyer")
		{
			$userInfo->firstName = $firstName;
			$userInfo->lastName = $lastName;
			$userInfo->infoComplete = true;
			$userInfo->__no_escape_data = array("infoPath" => "NULL");
		}
		else
		{
			$userInfo->companyName = $companyName;
			$userInfo->website = $website;
			$userInfo->description = $description;
			
			// No info, or the user switched from buyer
			if(!isset($user->info) || $user->isBuyer())
			{
				$userInfo->infoComplete = false;
				$userInfo->infoPath = "/user/location_manager";
			}
		}
		
		// First time
		if(!isset($user->info))
		{
			$userInfo->newBidNotify = true;
			$userInfo->newMessageNotify = true;
			$userInfo->bidAcceptedNotify = true;
			$userInfo->srNotify = "radius";
			$userInfo->srNotifyRadius = 15;
		}

		$userInfo->save() or error();
		
		//------------------------------------------------------------------------------
		// Redirect to the correct place
		//------------------------------------------------------------------------------
		if($userInfo->infoComplete)
			$this->session->set_userdata("message", isset($user->info) ? $this->lang->line("user_info_success") : $this->lang->line("user_info_thanks"));
		
		redirect($userInfo->infoComplete ? "/user/manager" : "/user/location_manager", "location");
		exit();
	}
	
	/**
	 * Brings up the location manager
	 * 
	 * @return unknown_type
	 */
	public function location_manager()
	{	
		$this->userlib->check(UserLib::LEVEL_BUYER);
		
		$this->load->library("locationlib");
		
		//------------------------------------------------------------------------------
		// Load Form
		//------------------------------------------------------------------------------
		if($this->input->post("hdnLocations") === false)
			return gotoView("user/location_manager", "Location Manager");	

		//------------------------------------------------------------------------------
		// Submit Form - Validation
		//------------------------------------------------------------------------------
		$user = $this->userlib->getLoggedInUser();
		
		$this->form_validation->set_rules("hdnLocations", "Locations", "callback__validation_locations[" . ($user->isBuyer() ? "0" : "1") . "]");
		if(!$this->form_validation->run())
			return gotoView("user/location_manager", "Location Manager", array("error" => $this->lang->line("invalid_request")));	
	
		//------------------------------------------------------------------------------
		// Save Locations
		//------------------------------------------------------------------------------
		$this->load->orm("LocationModel");
		$locations = json_decode($this->input->post("hdnLocations"), true);
		
		$deleteLocations = hashArrayByObjectProperty($this->LocationModel->find_all_by("userId", $user->id), "id");
		$saveLocations = array();
		foreach($locations as $location)
		{
			if(!empty($location["id"]))
			{
				$locationModel = $this->LocationModel->find($location["id"]);
				if($locationModel->userId != $user->id)
					continue;
				
				unset($deleteLocations[$location["id"]]);
			}
			else
				$locationModel = $this->LocationModel->new_record();

			$locationModel->load_data(cleanXss($location));
			$locationModel->userId = $user->id;
			LocationModel::addGeocode($locationModel);
			
			$saveLocations[] = $locationModel;
			usleep(100000); // a little delay so that we don't hammer google's geocoder
		}
		
		foreach($saveLocations as $saveLocation)
			$saveLocation->save() or error();

		foreach($deleteLocations as $deleteLocation)
		{
			if($this->locationlib->canDeleteLocation($deleteLocation))
			{
				$deleteLocation->delete() or error();
			}
			else
			{
				$deleteLocation->__no_escape_data = array("userId" => "NULL");
				$deleteLocation->save() or error();
			}
		}

		//------------------------------------------------------------------------------
		// Set the info path
		//------------------------------------------------------------------------------	
		if(!$user->info->infoComplete)
		{
			$userInfoModel = $this->UserInfoModel->find($user->id);
			$userInfoModel->infoPath = "/user/category_manager";
			$userInfoModel->save() or error();
		}	
		
		//------------------------------------------------------------------------------
		// Redirect to the correct place
		//------------------------------------------------------------------------------	
		if($user->info->infoComplete)
			$this->session->set_userdata("message", $this->lang->line("user_info_success"));
		
		redirect($user->info->infoComplete ? "/user/manager" : "/user/category_manager", "location");
		exit();
	}
	
	/**
	 * Brings up the category manager
	 * 
	 * @return unknown_type
	 */
	public function category_manager()
	{
		$this->userlib->check(UserLib::LEVEL_SELLER);

		$this->load->library("categorylib");
			
		//------------------------------------------------------------------------------
		// Load Form
		//------------------------------------------------------------------------------
		if($this->input->post("chkCategories") === false)
			return gotoView("user/category_manager", "Category Manager");	

		//------------------------------------------------------------------------------
		// Submit Form - Validation
		//------------------------------------------------------------------------------
		$user = $this->userlib->getLoggedInUser();
		
		$this->form_validation->set_rules("chkCategories", "Categories", "required|callback__validation_categories");
		if(!$this->form_validation->run())
			return gotoView("user/category_manager", "Category Manager", array("error" => $this->lang->line("invalid_request")));	

		//------------------------------------------------------------------------------
		// Save Form
		//------------------------------------------------------------------------------
		
		// First strip out all children
		$categoryIds = array_flip($this->input->post("chkCategories"));
		$parentMap = $this->categorylib->getParentMap();
		foreach($categoryIds as $categoryId => $dummy)
		{
			if(isset($parentMap[$categoryId]))
			{
				foreach($parentMap[$categoryId] as $child)
					if(isset($categoryIds[$child->id]))
						unset($categoryIds[$child->id]);
			}
		}
		
		$this->load->orm("UserCategoryModel");
		UserCategoryModel::deleteByUser($user);
		foreach($categoryIds as $categoryId => $dummy)
		{
			$userCategory = $this->UserCategoryModel->new_record();
			$userCategory->userId = $user->id;
			$userCategory->categoryId = $categoryId;
			$userCategory->save() or error();
		}

		//------------------------------------------------------------------------------
		// Set the info path and flag
		//------------------------------------------------------------------------------	
		$this->session->set_userdata("message", $this->lang->line("user_info_success"));
		if(!$user->info->infoComplete)
		{
			$userInfoModel = $this->UserInfoModel->find($user->id);
			$userInfoModel->infoComplete = true;
			$userInfoModel->__no_escape_data = array("infoPath" => "NULL");
			$userInfoModel->save() or error();
			
			$this->session->set_userdata("message", $this->lang->line("user_info_thanks"));
		}
		
		redirect("/user/manager", "location");
		exit();
	}
	
	/**
	 * Notification settings
	 * 
	 * @return unknown_type
	 */
	public function notification_manager()
	{
		$this->userlib->check(UserLib::LEVEL_BUYER);
		
		if($this->input->post("hdnDummy") === false)
			return gotoView("user/notification_manager", "Notification Settings");
			
		//------------------------------------------------------------------------------
		// Submit Form - Validation
		//------------------------------------------------------------------------------
		$user = $this->userlib->getLoggedInUser();
		
		if($user->isSeller())
		{
			$this->form_validation->set_rules("rdbSRNotify", "Auto Notify", "required");
			if($this->input->post("rdbSRNotify") == "radius")
				$this->form_validation->set_rules("txtRadius", "Radius", "required|is_natural_no_zero");
			
			if(!$this->form_validation->run())
				return gotoView("user/notification_manager", "Notification Settings", array("error" => $this->lang->line("invalid_request")));
		}

		//------------------------------------------------------------------------------
		// Save auto notify
		//------------------------------------------------------------------------------
		$userInfo = $this->UserInfoModel->find($user->id);
		$userInfo->newBidNotify = $this->input->post("chkNewBidNotify") == "on";
		$userInfo->newMessageNotify = $this->input->post("chkNewMessageNotify") == "on";
		
		if($user->isSeller())
		{
			$userInfo->bidAcceptedNotify = $this->input->post("chkBidAcceptedNotify") == "on";
			$userInfo->srNotify = $this->input->post("rdbSRNotify");
			if($userInfo->srNotify == "radius")
				$userInfo->srNotifyRadius = $this->input->post("txtRadius");
			else
				$userInfo->__no_escape_data = array("srNotifyRadius" => "NULL");
		}
			
		$userInfo->save() or error();

		//------------------------------------------------------------------------------
		// Redirect to the correct place
		//------------------------------------------------------------------------------
		$this->session->set_userdata("message", $this->lang->line("user_info_success"));
		redirect("/user/manager", "location");
		exit();
	}
	
	/**
	 * User account control panel
	 * 
	 * @return unknown_type
	 */
	public function manager()
	{
		$this->userlib->check(UserLib::LEVEL_BUYER);
		
		$this->load->library("bidlib");
		$this->load->library("servicerequestlib");
		$this->load->library("notificationlib");
		
		gotoView("user/manager", "Manager");
	}

	/**
	 * User privacy information page
	 * 
	 * @return unknown_type
	 */
	public function privacy()
	{
		gotoView("user/privacy", "User Privacy");
	}
	
	/**
	 * Removes a notification
	 * 
	 * @param $notificationId
	 * @return unknown_type
	 */
	public function remove_notification($notificationId = null)
	{
		$this->userlib->check(UserLib::LEVEL_BUYER);
		
		if($notificationId == null)
		{
			echo "Error";
			return;
		}
		
		$this->load->orm("NotificationModel");

		$user = $this->userlib->getLoggedInUser();
		$notificationModel = $this->NotificationModel->find($notificationId);
		if(empty($notificationModel) || $notificationModel->userId != $user->id)
		{
			echo "Error";
			return;
		}
			
		$notificationModel->delete() or error();
		echo $notificationId;
	}
	
	//------------------------------------------------------------------------------
	// CUSTOM VALIDATION METHODS
	//------------------------------------------------------------------------------

	/**
	 * Validates that the user type is a valid string
	 * 
	 * @param $str
	 * @return unknown_type
	 */
	public function _validation_userType($str)
	{
		if ($str != "buyer" && $str != "seller")
		{
			$this->form_validation->set_message('_validation_userType', 'User type has to either be buyer or seller');
			return false;
		}
		
		return true;
	}
	
	/**
	 * Custom validation for the locations field. This field is a json string
	 * so it takes some logic to analyze it's validity.
	 * 
	 * @param $str
	 * @return unknown_type
	 */
	public function _validation_locations($str, $isSeller)
	{
		$locations = json_decode($str);
		if($locations === null || !is_array($locations))
		{
			$this->form_validation->set_message("_validation_locations", "Couldn't decode locations");
			return false;
		}
		
		if(empty($locations) && $isSeller)
		{
			$this->form_validation->set_message("_validation_locations", "At least one location is required");
			return false;
		}

		$valid = true;
		foreach($locations as $location)
		{
			$valid = !$valid || empty($location->street1) ? false : true; 
			$valid = !$valid || empty($location->city) ? false : true; 
			$valid = !$valid || empty($location->region) ? false : true; 
			$valid = !$valid || empty($location->postalCode) ? false : true; 
			$valid = !$valid || empty($location->country) ? false : true; 

			if(!$valid)
			{
				$this->form_validation->set_message("_validation_locations", "The locations are invalid");
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Custom validation for the categories field. This fiels is a comma separated
	 * list of category ids
	 * 
	 * @param $str
	 * @return unknown_type
	 */
	public function _validation_categories($categoryIds)
	{
		$this->load->library("categorylib");
		$categories = hashArrayByObjectProperty($this->categorylib->getCategories(), "id");
		foreach($categoryIds as $categoryId)
		{
			if(!isset($categories[$categoryId]))
			{
				$this->form_validation->set_message("_validation_categories", "Invalid categories");
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Custom validation for the rating field
	 * 
	 * @param $str
	 * @return unknown_type
	 */
	public function _validation_rating($str)
	{
		if(empty($str))
			return true;
		
		$val = intval($str);
		if(!CI_Form_validation::is_natural_no_zero($str) || $val <= 0 || $val > 5)
		{
			$this->form_validation->set_message("_validation_rating", "The rating is invalid");
			return false;
		}
		
		return true;
	}
}

?>