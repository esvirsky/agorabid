<?php

/**
 * This controller is responsible for handling service requests - creation, searching, ....
 *
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class Service_Request extends Controller
{
	/**
	 * Number of search results to display
	 * @var unknown_type
	 */
	const SEARCH_RESULTS = 25;
	
	const SUBMIT_TYPE_CREATE = "create";
	const SUBMIT_TYPE_MODIFY = "modify";
	
	/*
 	 * Constructor - holds common functions for all controller interfaces
	 */
	public function __construct()
	{
		parent::Controller();
		$this->lang->load('service_request', 'english');
	}
	
	/**
	 * Accept a bid
	 * 
	 * @param $bidId
	 * @return unknown_type
	 */
	public function accept_bid($bidId = null)
	{
		$this->userlib->check(UserLib::LEVEL_BUYER);
		
		//------------------------------------------------------------------------------
		// Loading and Validation
		//------------------------------------------------------------------------------

		if($bidId == null)
			return messageView("Error", $this->lang->line("invalid_request"));
			
		$this->load->library("bidlib");
		$this->load->library("servicerequestlib");
		
		$bid = $this->bidlib->getBidById($bidId, true);
		if($bid == null || $bid->accepted)
			return messageView("Error", $this->lang->line("invalid_request"));
			
		$user = $this->userlib->getLoggedInUser();
		if($bid->serviceRequest->userId != $user->id)
			return messageView("Error", $this->lang->line("invalid_request"));
		
			
		//------------------------------------------------------------------------------
		// Accept the bid
		//------------------------------------------------------------------------------
		
		$this->load->orm("BidModel");
		$bidModel = $this->BidModel->find($bid->id);
		$bidModel->accepted = true;
		$bidModel->__no_escape_data = array("acceptedDate" => "NOW()");
		$bidModel->save() or error();
		
		$this->load->orm("ServiceRequestModel");
		$srModel = $this->ServiceRequestModel->find($bid->serviceRequest->id);
		$srModel->status = ServiceRequestLib::STATUS_CLOSED;
		$srModel->save() or error();
		
		$this->load->library("notificationlib");
		$this->_createNotification($bid->user, NotificationLib::TYPE_BID_ACCEPTED, null, $bid);
		
		$this->session->set_userdata("message", $this->lang->line("sr_accepted_bid"));
		redirect("/service_request/details/" . $bid->serviceRequest->id . "#bid" . $bidModel->uid());
	}
	
	/**
	 * Create a service request - eventually goes to submit to do the actual submit
	 * 
	 * @return unknown_type
	 */
	public function create($page = null)
	{
		$this->load->library("categorylib");

		$data = array("type" => Service_Request::SUBMIT_TYPE_CREATE, "page" => $page == 2 ? 2 : 1);
		
		if($this->input->get("categoryId") !== false)
			$data["selectedCategory"] = $this->categorylib->getCategoryById($this->input->get("categoryId"));

		if($page == 2)
		{
			$this->_setRulesSubmit1();
			if(!$this->form_validation->run())
				return messageView("Error", $this->lang->line("invalid_request"));
		}
				
		$this->load->view('service_request/submit', $data);
	}
	
	/**
	 * Confirm or reject the creation of a service request that was created pre-login
	 * 
	 * @return unknown_type
	 */
	public function create_confirm($confirm = null)
	{
		// Can't check it with the default, because user can confirm a service request before entering personal info
		if($this->userlib->getLoggedInUser() == null)
			return messageView("Error", $this->lang->line("invalid_request"));
		
		if($this->session->userdata("createServiceRequest") === false)
			return messageView("Error", $this->lang->line("invalid_request"));

		$this->load->library("servicerequestlib");
		$serviceRequest = json_decode($this->session->userdata("createServiceRequest"));	
			
		if($confirm == null)
		{
			$this->load->library("categorylib");
			$categories = hashArrayByObjectProperty($this->categorylib->getCategories(), "id");
			$data = array("url" => "/service_request/create_confirm");
			$data["question"] = str_replace(array("{title}", "{category}", "{description}"),
											array($serviceRequest->title, $categories[$serviceRequest->categoryId]->name, nl2br(shortenString($serviceRequest->description, 75))),
											$this->lang->line("sr_create_confirm"));
			return gotoView("/general/confirm", "Create Service Request?", $data);
		}
		else if($confirm == "no")
		{
			$this->session->unset_userdata("createServiceRequest");
			redirect("/user/manager", "location");
		}
		else if($confirm == "yes")
		{
			$serviceRequest->userId = $this->userlib->getLoggedInUser()->id;
			$srModel = $this->servicerequestlib->saveServiceRequest($serviceRequest);
			$this->session->unset_userdata("createServiceRequest");
			$this->session->set_userdata("message", $this->lang->line("sr_created"));
			redirect("/service_request/details/" . $srModel->uid(), "location");
		}
	}
	
	/**
	 * Create a bid
	 * 
	 * @param $serviceRequestId
	 * @return unknown_type
	 */
	public function create_bid($serviceRequestId = null)
	{
		$this->userlib->check(UserLib::LEVEL_SELLER);
		
		//------------------------------------------------------------------------------
		// Validate service request
		//------------------------------------------------------------------------------
		
		if($serviceRequestId == null)
			return messageView("Error", $this->lang->line("invalid_request"));
		
		$this->load->library("locationlib");
		$this->load->library("servicerequestlib");
		$this->load->library("bidlib");
			
		$serviceRequest = $this->servicerequestlib->getServiceRequestById($serviceRequestId);
		if($serviceRequest == null || !$serviceRequest->isOpen())
			return messageView("Error", $this->lang->line("invalid_request"));
		
		//------------------------------------------------------------------------------
		// Validate that person doesn't already have a bid
		//------------------------------------------------------------------------------
		
		$user = $this->userlib->getLoggedInUser();
		$bid = $this->bidlib->getBidByUserServiceRequest($user, $serviceRequest);
		if($bid != null)
			return messageView("Error", $this->lang->line("sr_only_one_bid"));
		
		//------------------------------------------------------------------------------
		// Validate the inputs
		//------------------------------------------------------------------------------
			
		$price = $this->input->post("txtPrice");
		$time = $this->input->post("txtTime");
		$note = $this->input->post("txtNote");
		$locationId = $this->input->post("listLocations");
		$pricePrecision = $this->input->post("rdbPricePrecision");
		$timePrecision = $this->input->post("rdbTimePrecision");
		
		$this->form_validation->set_rules("listLocations", "Locations", "required|is_natural_no_zero");
		if(!$this->form_validation->run())
			return messageView("Error", $this->lang->line("invalid_request"));
		
		if((!empty($price) && $pricePrecision != "estimate" && $pricePrecision != "exact") || (!empty($time) && $timePrecision != "estimate" && $timePrecision != "exact"))
			return messageView("Error", $this->lang->line("invalid_request"));
		
		if(empty($price) && empty($time) && empty($note))
			return messageView("Error", $this->lang->line("invalid_request"));
			
		$location = $this->locationlib->getLocationById($locationId);
		if($location == null || $location->userId != $user->id)
			return messageView("Error", $this->lang->line("invalid_request"));
		
		//------------------------------------------------------------------------------
		// Create and save the bid
		//------------------------------------------------------------------------------
		$this->load->orm("BidModel");
		$bidModel = $this->BidModel->new_record();
		$bidModel->userId = $user->id;
		$bidModel->serviceRequestId = $serviceRequestId;
		$bidModel->locationId = $this->input->post("listLocations");
		$bidModel->price = $this->input->xss_clean($price);
		$bidModel->pricePrecision = !empty($price) ? $pricePrecision : null;
		$bidModel->time = $this->input->xss_clean($time);
		$bidModel->timePrecision = !empty($time) ? $timePrecision : null;
		$bidModel->note = $this->input->xss_clean($note);
		$bidModel->accepted = false;
		$bidModel->save() or error();
		
		// if the person already has a message thread just add the bidId to that thread
		$this->load->library("messagelib");
		$messageThread = $this->messagelib->getMessageThreadByUserServiceRequest($user, $serviceRequest);
		if($messageThread != null)
		{
			$mtModel = $this->MessageThreadModel->find($messageThread->id);
			$mtModel->__no_escape_data = array("serviceRequestId" => "NULL");
			$mtModel->bidId = $bidModel->uid();
			$mtModel->save() or error();
		}

		// add a notification for the person that created the service request
		$this->load->library("notificationlib");
		$this->_createNotification($serviceRequest->user, NotificationLib::TYPE_NEW_BID, null, $this->bidlib->getBidById($bidModel->uid()));

		$this->session->set_userdata("message", $this->lang->line("sr_bid_created"));
		redirect("/service_request/details/" . $serviceRequest->id . "#bid" . $bidModel->uid());
	}
	
	/**
	 * Create a bid message
	 * 
	 * @param $bidId
	 * @return unknown_type
	 */
	public function create_bid_message($bidId = null)
	{
		$this->userlib->check(UserLib::LEVEL_BUYER);
		
		//------------------------------------------------------------------------------
		// Loading and Validation
		//------------------------------------------------------------------------------
		
		if($bidId == null)
			return messageView("Error", $this->lang->line("invalid_request"));
			
		$this->load->library("bidlib");
		
		$bid = $this->bidlib->getBidById($bidId, true);
		if($bid == null)
			return messageView("Error", $this->lang->line("invalid_request"));
			
		$user = $this->userlib->getLoggedInUser();	

		// user has to be either the creator of the bid or the creator of the service request
		if($bid->userId != $user->id && $bid->serviceRequest->userId != $user->id)
			return messageView("Error", $this->lang->line("invalid_request"));

		$messageField = "txtMessage_bid" . $bidId;
		$publicCheckbox = "chkPublicMessage_bid" . $bidId;
		$message = $this->input->xss_clean($this->input->post($messageField));
		if(!$this->_validateCreateMessage($messageField))
			return messageView("Error", $this->lang->line("invalid_request"));
			
		//------------------------------------------------------------------------------
		// Create and save message
		//------------------------------------------------------------------------------
		$this->load->library("messagelib");
			
		$messageThread = $this->messagelib->getMessageThreadByBid($bid);
		if($messageThread == null)
			$messageThread = $this->_createMessageThread($user, null, $bid);
			
		$message = $this->_createMessage($messageThread, $user, $bid->userId == $user->id ? $bid->serviceRequest->user : $bid->user, $message);
		
		// Make a message public if it's needed
		if($user->id == $bid->serviceRequest->userId && $this->input->post($publicCheckbox))
			$this->_createPublicMessage($bid->serviceRequest, $message);
			
		$this->session->set_userdata("message", $this->lang->line("sr_message_created"));
		redirect("/service_request/details/" . $bid->serviceRequest->id . "#message" . $message->id);
	}
	
	/**
	 * Create a service request message
	 * 
	 * @param $serviceRequestId
	 * @return unknown_type
	 */
	public function create_service_request_message($serviceRequestId = null)
	{
		$this->userlib->check(UserLib::LEVEL_BUYER);
		
		//------------------------------------------------------------------------------
		// Loading and Validation
		//------------------------------------------------------------------------------
		
		if($serviceRequestId == null)
			return messageView("Error", $this->lang->line("invalid_request"));
			
		$this->load->library("servicerequestlib");
		$this->load->library("bidlib");
		
		$serviceRequest = $this->servicerequestlib->getServiceRequestById($serviceRequestId, true);
		if($serviceRequest == null || !$serviceRequest->isOpen())
			return messageView("Error", $this->lang->line("invalid_request"));
			
		$user = $this->userlib->getLoggedInUser();	
		
		// User has to be a seller but not the creator of this service request
		if($serviceRequest->userId == $user->id || !$user->isSeller())
			return messageView("Error", $this->lang->line("invalid_request"));

		// User can't post a service request message if they already have a bid
		$bid = $this->bidlib->getBidByUserServiceRequest($user, $serviceRequest);
		if($bid != null)
			return messageView("Error", $this->lang->line("invalid_request"));	
			
		$messageField = "txtMessage_service_request" . $serviceRequestId;
		$publicCheckbox = "chkPublicMessage_service_request" . $serviceRequestId;
		$message = $this->input->xss_clean($this->input->post($messageField));
		if(!$this->_validateCreateMessage($messageField))
			return messageView("Error", $this->lang->line("invalid_request"));
			
		//------------------------------------------------------------------------------
		// Create and save message
		//------------------------------------------------------------------------------
		$this->load->library("messagelib");
		
		$messageThread = $this->messagelib->getMessageThreadByUserServiceRequest($user, $serviceRequest);
		if($messageThread == null)
			$messageThread = $this->_createMessageThread($user, $serviceRequest, null);

		$message = $this->_createMessage($messageThread, $user, $serviceRequest->user, $message);
		
		// Make a message public if it's needed
		if($user->id == $serviceRequest->userId && $this->input->post($publicCheckbox))
			$this->_createPublicMessage($serviceRequest, $message);
		
		$this->session->set_userdata("message", $this->lang->line("sr_message_created"));
		redirect("/service_request/details/" . $serviceRequest->id . "#message" . $message->id);
	}
	
	/**
	 * Create a message thread message
	 * 
	 * @param $messageThreadId
	 * @return unknown_type
	 */
	public function create_message_thread_message($messageThreadId = null)
	{
		$this->userlib->check(UserLib::LEVEL_BUYER);
		
		//------------------------------------------------------------------------------
		// Loading and Validation
		//------------------------------------------------------------------------------
		
		if($messageThreadId == null)
			return messageView("Error", $this->lang->line("invalid_request"));
			
		$this->load->library("messagelib");
		
		$messageThread = $this->messagelib->getMessageThreadById($messageThreadId, true);
		if($messageThread == null)
			return messageView("Error", $this->lang->line("invalid_request"));
			
		$user = $this->userlib->getLoggedInUser();	
		$serviceRequest = isset($messageThread->serviceRequest) ? $messageThread->serviceRequest : $messageThread->bid->serviceRequest;
		
		// User has to either be the creator of this message thread, creator of the associated service request, or creator of the associated bid
		if($serviceRequest->userId != $user->id && $messageThread->userId != $user->id && (!isset($messageThread->bid) || $messageThread->bid->userId != $user->id))
			return messageView("Error", $this->lang->line("invalid_request"));
		
		$messageField = "txtMessage_message_thread" . $messageThreadId;
		$publicCheckbox = "chkPublicMessage_message_thread" . $messageThreadId;
		$message = $this->input->xss_clean($this->input->post($messageField));
		if(!$this->_validateCreateMessage($messageField))
			return messageView("Error", $this->lang->line("invalid_request"));
			
		// Create and save message
		if($serviceRequest->userId == $user->id && $messageThread->bid != null)
			$recipient = $messageThread->bid->user;
		else if($serviceRequest->userId == $user->id)
			$recipient = $messageThread->user;
		else 
			$recipient = $serviceRequest->user;
			
		$message = $this->_createMessage($messageThread, $user, $recipient, $message);
		
		// Make a message public if it's needed
		if($user->id == $serviceRequest->userId && $this->input->post($publicCheckbox))
			$this->_createPublicMessage($serviceRequest, $message);
		
		$this->session->set_userdata("message", $this->lang->line("sr_message_created"));
		redirect("/service_request/details/" . $serviceRequest->id . "#message" . $message->id);
	}
	
	/**
	 * Delete a service request
	 * 
	 * @return unknown_type
	 */
	public function delete($id = null)
	{
		$this->userlib->check(UserLib::LEVEL_BUYER);
		
		$serviceRequest = $this->_getServiceRequest($id);
		
		if(!$this->_canModify($serviceRequest))
			return messageView("Error", $this->lang->line("invalid_request"));
		
		if(!$this->servicerequestlib->canDeleteServiceRequest($serviceRequest))
			return messageView("Error", $this->lang->line("invalid_request"));

		$location = $serviceRequest->location;
			
		// Delete service request
		$this->load->orm("ServiceRequestModel");
		$serviceRequestModel = $this->ServiceRequestModel->find($id);
		$serviceRequestModel->delete() or error();
		
		// Delete location
		$this->load->library("locationlib");
		$this->load->orm("LocationModel");
		if($this->locationlib->canDeleteLocation($location) && $location->userId == null)
			$this->LocationModel->find($location->id)->delete() or error();
		
		messageView("Service Request Deleted", $this->lang->line("sr_deleted"));
	}
	
	/**
	 * Details of a service requests
	 * 
	 * @return unknown_type
	 */
	public function details($id = null)
	{	
		$user = $this->userlib->getLoggedInUser();
		if($user != null)
			$this->userlib->checkUserInfo($user);
		
		if($id == null)
			return messageView("Error", $this->lang->line("invalid_request"));
			
		$this->load->library("servicerequestlib");
		$this->load->library("bidlib");
		$this->load->library("messagelib");
		$this->load->library("notificationlib");
		$this->load->library("googlemapslib");
	
		$serviceRequest = $this->servicerequestlib->getServiceRequestById($id, true);
		if($serviceRequest == null)
			return messageView("Error", $this->lang->line("invalid_request"));

		gotoView("service_request/details", null, array("serviceRequest" => $serviceRequest));
	}
	
	/**
	 * Ends bidding for service request
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	public function end_bidding($id = null)
	{
		$this->userlib->check(UserLib::LEVEL_BUYER);
		
		$serviceRequest = $this->_getServiceRequest($id);
		
		if(!$this->_canModify($serviceRequest))
			return messageView("Error", $this->lang->line("invalid_request"));
		
		if($this->servicerequestlib->canDeleteServiceRequest($serviceRequest))
			return messageView("Error", $this->lang->line("invalid_request"));	
			
		$this->load->orm("ServiceRequestModel");
		$serviceRequestModel = $this->ServiceRequestModel->find($id);
		$serviceRequestModel->status = ServiceRequestLib::STATUS_CLOSED;
		$serviceRequestModel->save() or error();
		
		$this->session->set_userdata("message", $this->lang->line("sr_closed"));
		redirect("/service_request/details/" . $serviceRequest->id);
	}
	
	/**
	 * Modify a service request
	 * 
	 * @return unknown_type
	 */
	public function modify($id = null)
	{
		$this->userlib->check(UserLib::LEVEL_BUYER);
		
		$this->load->library("categorylib");
		$this->load->library("servicerequestlib");
		
		$serviceRequest = $this->_getServiceRequest($id);
		
		if(!$this->_canModify($serviceRequest))
			return messageView("Error", $this->lang->line("invalid_request"));
		
		$this->load->view('service_request/submit', array("type" => Service_Request::SUBMIT_TYPE_MODIFY, "serviceRequest" => $serviceRequest));
	}
	
	/**
	 * Reopens the service request for bidding
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	public function reopen($id = null)
	{
		$this->userlib->check(UserLib::LEVEL_BUYER);
		
		if($id == null)
			return messageView("Error", $this->lang->line("invalid_request"));

		$this->load->library("servicerequestlib");
			
		$user = $this->userlib->getLoggedInUser();
		$serviceRequest = $this->servicerequestlib->getServiceRequestById($id);
		if($serviceRequest == null || $user->id != $serviceRequest->userId || $serviceRequest->isOpen())
			return messageView("Error", $this->lang->line("invalid_request"));
		
		$this->load->orm("ServiceRequestModel");
		$serviceRequestModel = $this->ServiceRequestModel->find($id);
		$serviceRequestModel->status = ServiceRequestLib::STATUS_OPEN;
		$serviceRequestModel->save() or error();
		
		$this->session->set_userdata("message", $this->lang->line("sr_reopened"));
		redirect("/service_request/details/" . $serviceRequest->id);
	}
	
	/**
	 * search/browse service requests
	 * 
	 * @return unknown_type
	 */
	public function search()
	{
		$this->load->library("categorylib");
		
		if($this->input->get("category") !== false)
		{
			// Have to do this for validation
			$_POST = $_GET;
			
			$this->form_validation->set_rules("category", "Category", "required|is_natural");
			$this->form_validation->set_rules("page", "Page", "is_natural_no_zero");
			if($this->input->get("everywhere") === false)
			{
				$this->form_validation->set_rules("address", "Address", "required");
				$this->form_validation->set_rules("radius", "Radius", "required|is_natural");
			}
		
			if(!$this->form_validation->run())
				return messageView("Error", $this->lang->line("invalid_request"));
				
			$category = $this->categorylib->getCategoryById($this->input->get("category"));
			if($category == null)
				return messageView("Error", $this->lang->line("invalid_request"));

			$page = $this->input->get("page");
			if($page === false)
				$page = 1;
		
			$this->load->library("servicerequestlib");
			
			$this->servicerequestlib->model->offset(($page-1)*Service_Request::SEARCH_RESULTS);
			$this->servicerequestlib->model->limit(Service_Request::SEARCH_RESULTS+1); // +1 so that we know to show next or not
			$this->servicerequestlib->model->order_by("created", "DESC");
			$this->servicerequestlib->model->where("ServiceRequests.status", ServiceRequestLib::STATUS_OPEN);
			
			if($this->input->get("everywhere"))
				$serviceRequests = $this->servicerequestlib->getServiceRequestsByCategory($category);
			else
			{
				$this->load->library("googlemapslib");
				try
				{	
					$ar = $this->googlemapslib->getLatLong($this->input->get("address"));
					if($ar["status"] == 200)
						$serviceRequests = $this->servicerequestlib->getServiceRequestsByCategoryRadius($category, $ar["latitude"], $ar["longitude"], $this->input->get("radius"));
					else
						return messageView("Error", $this->lang->line("default_error"));
				}
				catch(ConnectionException $ex)
				{
					return messageView("Error", $this->lang->line("default_error"));
				}
			}
		}
	
		$data = array();
		if(isset($serviceRequests))
		{
			$data["page"] = $page;
			$data["next"] = count($serviceRequests) > Service_Request::SEARCH_RESULTS;
			$data["serviceRequests"] = count($serviceRequests) > Service_Request::SEARCH_RESULTS ? array_slice($serviceRequests, 0, Service_Request::SEARCH_RESULTS) : $serviceRequests;
		}
	
		return gotoView("service_request/search", "Search", $data);
	}

	/**
	 * Submit a service request
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	public function submit($id = null)
	{
		$this->load->library("categorylib");
		$this->load->library("servicerequestlib");
		$this->load->orm("LocationModel");
		
		$serviceRequest = null;
		$isCreate = $id == null;
		$user = $this->userlib->getLoggedInUser();
		
		if($id != null)
		{
			$this->userlib->check(UserLib::LEVEL_BUYER);
			$serviceRequest = $this->_getServiceRequest($id);
			if(!$this->_canModify($serviceRequest))
				return messageView("Error", $this->lang->line("invalid_request"));
		}

		$this->_setRulesSubmit1();
		$this->form_validation->set_rules("rdbSRLocation", "Service Request Location", "required");
		$this->form_validation->set_rules("txtCity", "City", "required");
		$this->form_validation->set_rules("ddnState", "State", "required");
		$this->form_validation->set_rules("txtPostalCode", "Potal Code", "required|is_natural");
		$this->form_validation->set_rules("ddnCountry", "Country", "required");
		if(!$this->form_validation->run())
			return messageView("Error", $this->lang->line("invalid_request"));

		$serviceRequest = $isCreate ? new ServiceRequestEntity() : $serviceRequest;
		$serviceRequest->title = $this->input->xss_clean($this->input->post("txtTitle"));
		$serviceRequest->description = $this->input->xss_clean($this->input->post("txtDescription"));
		$serviceRequest->categoryId =  $this->input->post("txtCategory");
		$serviceRequest->atLocation = $this->input->post("rdbSRLocation") == "mine" ? true : false;
		$serviceRequest->userId = $user == null ? null : $user->id;
		$serviceRequest->status = $isCreate ? ServiceRequestLib::STATUS_OPEN : $serviceRequest->status;

		$serviceRequest->location = $isCreate ? new LocationEntity() : $serviceRequest->location;
		$serviceRequest->location->street1 = $this->input->xss_clean($this->input->post("txtStreet1"));
		$serviceRequest->location->street2 = $this->input->xss_clean($this->input->post("txtStreet2"));
		$serviceRequest->location->city = $this->input->xss_clean($this->input->post("txtCity"));
		$serviceRequest->location->postalCode = $this->input->xss_clean($this->input->post("txtPostalCode"));
		$serviceRequest->location->region = $this->input->xss_clean($this->input->post("ddnState"));
		$serviceRequest->location->country = $this->input->xss_clean($this->input->post("ddnCountry"));
		LocationModel::addGeocode($serviceRequest->location);	

		if($user == null)
		{
			$this->session->set_userdata("createServiceRequest", json_encode($serviceRequest));
			$this->session->set_userdata("message", $this->lang->line("sr_create_login"));
			redirect("/auth/login", "location");	
		}
		else
		{
			$srModel = $this->servicerequestlib->saveServiceRequest($serviceRequest);
			$this->session->set_userdata("message", $this->lang->line(isset($serviceRequest->id) ? "sr_saved" : "sr_created"));
			redirect("/service_request/details/" . $srModel->uid(), "location");
		}
	}
		
	/**
	 * Validates the message fields for a create message request
	 * 
	 * @param $messageField
	 * @return unknown_type
	 */
	private function _validateCreateMessage($messageField)
	{
		$this->form_validation->set_rules($messageField, "Message", "required");
		return $this->form_validation->run();
	}
	
	/**
	 * Sets validation rules for submit1
	 */
	private function _setRulesSubmit1()
	{
		$this->form_validation->set_rules("txtTitle", "Title", "required");
		$this->form_validation->set_rules("txtDescription", "Description", "required");
		$this->form_validation->set_rules("txtCategory", "Categories", "required|callback__validation_category");
	}
	
	/**
	 * Can the logged in user modify the passed in service request
	 * 
	 * @param $serviceRequest
	 * @return unknown_type
	 */
	private function _canModify($serviceRequest)
	{
		$user = $this->userlib->getLoggedInUser();
		return ($serviceRequest != null && $user->id == $serviceRequest->userId && $serviceRequest->isOpen());
	}
	
	/**
	 * Get's the service request by id. If $id is null returns null
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	private function _getServiceRequest($id)
	{
		if($id == null)
			null;

		$this->load->library("servicerequestlib");
		return $this->servicerequestlib->getServiceRequestById($id);
	}
		
	/**
	 * Creates and saves a message thread. Returns the message thread when it's done.
	 * 
	 * @param $user
	 * @param $serviceRequest
	 * @param $bid
	 * @return MessageThreadEntity
	 */
	private function _createMessageThread($user, $serviceRequest = null, $bid = null)
	{
		$this->load->orm("MessageThreadModel");
		$messageThreadModel = $this->MessageThreadModel->new_record();
		$messageThreadModel->userId = $user->id;
		
		if($bid != null)
			$messageThreadModel->bidId = $bid->id;
		
		if($serviceRequest != null)
			$messageThreadModel->serviceRequestId = $serviceRequest->id;
			
		$messageThreadModel->save() or error();
		
		$messageThreadModel->id = $messageThreadModel->uid();
		return toEntity($messageThreadModel);
	}
	
	/**
	 * Creates and saves a message. Returns the message.
	 * 
	 * @param $messageThread
	 * @param $sender
	 * @param $recipient
	 * @param $message
	 * @return MessageEntity
	 */
	private function _createMessage($messageThread, $sender, $recipient, $message)
	{
		$this->load->orm("MessageModel");

		$messageModel = $this->MessageModel->new_record();
		$messageModel->messageThreadId = $messageThread->id;
		$messageModel->senderId = $sender->id;
		$messageModel->recipientId = $recipient->id;
		$messageModel->message = $message;
		$messageModel->save() or error();
		
		$messageModel->id = $messageModel->uid();
		$messageEntity = toEntity($messageModel);
		
		// add a message notification for the recipient
		$this->load->library("notificationlib");
		$this->_createNotification($recipient, NotificationLib::TYPE_NEW_MESSAGE, $messageEntity, null);
		
		return $messageEntity;
	}
	
	/**
	 * Creates a notification
	 * 
	 * @param $user
	 * @param $type
	 * @param $message
	 * @param $bid
	 * @return NotificationEntity
	 */
	private function _createNotification($user, $type, $message, $bid)
	{
		$this->load->orm("NotificationModel");
		
		$notificationModel = $this->NotificationModel->new_record();
		$notificationModel->userId = $user->id;
		$notificationModel->type = $type;
		
		if($message != null)
			$notificationModel->messageId = $message->id;
			
		if($bid != null)
			$notificationModel->bidId = $bid->id;	

		$notificationModel->save() or error();

		//------------------------------------------------------------------------------
		// Possibly send an email - based on the recipients settings
		//------------------------------------------------------------------------------
		if($type == NotificationLib::TYPE_NEW_BID && $user->info->newBidNotify)
			$this->_sendEmailNotification($user, $type, $message, $bid);
		else if($type == NotificationLib::TYPE_NEW_MESSAGE && $user->info->newMessageNotify)
			$this->_sendEmailNotification($user, $type, $message, $bid);
		else if($type == NotificationLib::TYPE_BID_ACCEPTED && $user->info->bidAcceptedNotify)
			$this->_sendEmailNotification($user, $type, $message, $bid);
		
		$notificationModel->id = $notificationModel->uid();
		return toEntity($notificationModel);
	}
	
	/**
	 * Creates a public message. Just appends it to the service request description
	 * 
	 * @param $serviceRequest
	 * @param $message
	 * @return unknown_type
	 */
	private function _createPublicMessage($serviceRequest, $message)
	{
		$srModel = $this->ServiceRequestModel->find($serviceRequest->id);
		$srModel->description .= "\n\n<i><u>Public Message:</u></i> " . $message->message;
		$srModel->save() or error();
	}
	
	/**
	 * Send a user an email notification
	 * 
	 * @param $user
	 * @param $type
	 * @param $message
	 * @param $bid
	 * @return unknown_type
	 */
	private function _sendEmailNotification($user, $type, $message, $bid)
	{
		switch($type)
		{
			case NotificationLib::TYPE_NEW_MESSAGE:
				$langSubject = "sr_new_message_email_subject";
				$bodyMessage = str_replace(array("{message_creator}", "{message}"), array($message->sender->username, nl2br($message->message)), $this->lang->line("sr_new_message_email_body"));
				break;
			case NotificationLib::TYPE_NEW_BID:
				$langSubject = "sr_new_bid_email_subject";
				$bodyMessage = str_replace(array("{bid_creator}", "{sr_title}"), array($bid->user->username, $bid->serviceRequest->title), $this->lang->line("sr_new_bid_email_body"));
				break;	
			case NotificationLib::TYPE_BID_ACCEPTED:
				$bodyMessage = str_replace("{sr_title}", $bid->serviceRequest->title, $this->lang->line("sr_bid_accepted_email_body"));
				$langSubject = "sr_bid_accepted_email_subject";
				break;	
		}
		
		$body = str_replace(array("{site_name}", "{name}", "{base_url}", "{message}"), 
							array($this->config->item("site_name"), $user->username, base_url(), $bodyMessage), 
							$this->lang->line("sr_notification_email_body"));
		
		$this->load->library('email');
		$this->email->from($this->config->item('email_server_from'), $this->config->item('site_name'));
		$this->email->to($user->email);
		$this->email->subject($this->lang->line($langSubject));
		$this->email->message($body);
		$this->email->send() or error();
	}

	//------------------------------------------------------------------------------
	// CUSTOM VALIDATION METHODS
	//------------------------------------------------------------------------------
	
	/**
	 * Custom validation for the category field
	 * 
	 * @param $str
	 * @return unknown_type
	 */
	public function _validation_category($categoryId)
	{
		$this->load->library("categorylib");
		$categories = hashArrayByObjectProperty($this->categorylib->getCategories(), "id");
		if(!isset($categories[$categoryId]))
		{
			$this->form_validation->set_message("_validation_category", "Invalid category id");
			return false;
		}
		
		return true;
	}
}

?>