<?php

/**
 * This controller is used to run scripts and cron jobs on the site
 * 
 * @author esvirsky
 */
class Script extends Controller 
{
	const SCRIPT_PASS = "D8cK9_pz2387XVzcxvliu823584dfas23";
	
	/**
	 * Constructor
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		parent::Controller();
		$this->lang->load('script', 'english');
		set_time_limit(0); 
	}
	
	/**
	 * Daily cron. This cron closes service requests that have been inactive for too long ( 1 month at the moment )
	 * 
	 * @param $password
	 * @return unknown_type
	 */
	public function closeServiceRequests($password = null)
	{
		if($password != Script::SCRIPT_PASS)
			return;
			
		$date = "DATE_SUB(NOW(), INTERVAL 1 MONTH)";
		$query = <<<SQL
SELECT rs.*
FROM ServiceRequests rs WHERE
rs.status='open' AND
rs.id NOT IN (
	SELECT rs2.id
	FROM ServiceRequests rs2
	LEFT JOIN Bids b ON (b.serviceRequestId = rs2.id)
	LEFT JOIN MessageThreads mt ON (mt.serviceRequestId = rs2.id OR mt.bidId = b.id)
	LEFT JOIN Messages m ON (m.messageThreadId = mt.id)
	WHERE rs2.modified>$date
	OR b.modified>$date OR mt.modified>$date OR m.modified>$date
	GROUP BY rs2.id
)
SQL;

		$this->load->orm("ServiceRequestModel");
		$this->load->library("servicerequestlib");
		
		$srs = $this->ServiceRequestModel->find_all_by_sql($query);
		foreach($srs as $sr)
		{
			$sr->status = ServiceRequestLib::STATUS_CLOSED;
			$sr->save() or error();
		}
		
		echo "success";
	}
	
	/**
	 * Daily cron. Runs the review/feedback notification cron
	 * 
	 * @param $password
	 * @return unknown_type
	 */
	public function reviewNotification($password = null)
	{
		if($password != Script::SCRIPT_PASS)
			return;
	
		// Finds bids that have been accepted 1 or 2 weeks ago, but haven't been reviewed yet
		$query = <<<SQL
SELECT b.*
FROM Bids b
LEFT JOIN Reviews r ON (b.id = r.bidId)
WHERE b.accepted = '1'
AND r.id IS null
AND (b.acceptedDate BETWEEN DATE_SUB(NOW(), INTERVAL 8 DAY) AND DATE_SUB(NOW(), INTERVAL 7 DAY)
OR b.acceptedDate BETWEEN DATE_SUB(NOW(), INTERVAL 15 DAY) AND DATE_SUB(NOW(), INTERVAL 14 DAY))
SQL;

		$this->load->orm("BidModel");
		$this->lang->load('service_request', 'english');
		$bids = toEntities($this->BidModel->find_all_by_sql($query));
		foreach($bids as $bid)
			$this->_sendReviewNotificationEmail($bid);
			
		echo "success";	
	}
	
	/**
	 * Daily cron. Runs the sr notification cron, notifies sellers of new service requests
	 * in their area
	 * 
	 * @param $password
	 * @return unknown_type
	 */
	public function srNotification($password = null)
	{
		if($password != Script::SCRIPT_PASS)
			return;
	
		$this->lang->load('service_request', 'english');
		$this->load->library("categorylib");	
		$this->load->library("servicerequestlib");	
		
		// Run through every user
		$users = $this->userlib->getUsers();
		foreach($users as $user)
		{
			if(!isset($user->info) || $user->isBuyer() || $user->info->srNotify == UserLib::SR_NOTIFY_NONE)
				continue;
				
			$serviceRequests = array();
			foreach($user->categories as $category)
			{		
				if($user->info->srNotify == UserLib::SR_NOTIFY_ALL)
				{
					$this->_addSearchConditions($user);
					$serviceRequests = array_merge($serviceRequests, $this->servicerequestlib->getServiceRequestsByCategory($category));	
				}
				else
				{
					foreach($user->locations as $location)
					{
						$this->_addSearchConditions($user);
						$serviceRequests = array_merge($serviceRequests, $this->servicerequestlib->getServiceRequestsByCategoryRadius($category, $location->latitude, $location->longitude, $user->info->srNotifyRadius));
					}
				}
			}
	
			$serviceRequests = hashArrayByObjectProperty($serviceRequests, "id");
			if(!empty($serviceRequests))
				$this->_sendSrNotificationEmail($user, $serviceRequests);
		}
		
		echo "success";
	}
	
	/**
	 * Adds search conditions to service request searches
	 * 
	 * @param $user
	 * @return unknown_type
	 */
	private function _addSearchConditions($user)
	{
		$this->servicerequestlib->model->where("ServiceRequests.userId !=", $user->id);
		$this->servicerequestlib->model->where("ServiceRequests.status", ServiceRequestLib::STATUS_OPEN);
		$this->servicerequestlib->model->where("ServiceRequests.created >=", "DATE_SUB(NOW(), INTERVAL 1 DAY)", false);
	}
	
	/**
	 * Sends a review notification email
	 * 
	 * @return unknown_type
	 */
	private function _sendReviewNotificationEmail($bid)
	{
		$link = base_url() . "user/create_bid_review/" . $bid->id; 
		$message = str_replace(array("{seller}", "{sr_title}", "{review_link}"), 
								array($bid->user->username, $bid->serviceRequest->title, $link, $link), 
								$this->lang->line("script_review_notification_email_body"));

		$body = preg_replace('/\{site_name\} Customer Service(.|\n)*/', "{site_name} Customer Service", $this->lang->line("sr_notification_email_body"));											
		$body = str_replace(array("{name}", "{message}", "{site_name}", "{base_url}", "{base_url}"), 
							array($bid->serviceRequest->user->username, $message, $this->config->item("site_name"), base_url(), base_url()), 
							$body);		
							
		$this->load->library('email');
		$this->email->from($this->config->item('email_server_from'), $this->config->item('site_name'));
		$this->email->to($bid->serviceRequest->user->email);
		$this->email->subject($this->lang->line("script_review_notification_email_subject"));
		$this->email->message($body);
		$this->email->send() or error();
	}

	/**
	 * Sends new service requests notification
	 * @param $user
	 * @param $serviceRequests
	 * @return unknown_type
	 */
	private function _sendSrNotificationEmail($user, $serviceRequests)
	{
		$message = "<table>";
		foreach($serviceRequests as $serviceRequest)
		{
			$message .= "<tr>";
			$message .= "<td><b>name:</b> " . shortenString($serviceRequest->title, 25) . " &nbsp;&nbsp; </td>";
			$message .= "<td><b>where:</b> {$serviceRequest->location->city}, {$serviceRequest->location->region} &nbsp;&nbsp; </td>";
			$message .= "<td><b>type:</b> " . shortenString($serviceRequest->category->name, 25) . " &nbsp;&nbsp; </td>";
			$message .= "<td><a href='" . base_url() . "service_request/details/$serviceRequest->id'>view details</a></td>";
			$message .= "</tr>";
		}	
		$message .= "</table>";

		$fullMessage = str_replace("{sr_list}", $message, $this->lang->line("script_sr_notification_email_body"));
		$body = str_replace(array("{name}", "{message}", "{site_name}", "{base_url}", "{base_url}"), 
							array($user->username, $fullMessage, $this->config->item("site_name"), base_url(), base_url()), 
							$this->lang->line("sr_notification_email_body"));
		
		$this->load->library('email');
		$this->email->from($this->config->item('email_server_from'), $this->config->item('site_name'));
		$this->email->to($user->email);
		$this->email->subject($this->lang->line("script_sr_notification_email_subject"));
		$this->email->message($body);
		$this->email->send() or error();
	}
}
