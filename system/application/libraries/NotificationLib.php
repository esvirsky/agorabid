<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * A library that deals with notifications
 */
class NotificationLib
{
	const TYPE_NEW_BID = "new_bid";
	const TYPE_NEW_MESSAGE = "new_message";
	const TYPE_BID_ACCEPTED = "bid_accepted";
	
	public $model;
	
	private $_CI;
	
	/**
	 * Constructor
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		$this->_CI =& get_instance();
		$this->_CI->load->orm("NotificationModel");
		$this->model = $this->_CI->NotificationModel;
	}

	/**
	 * Gets a notification by id
	 * 
	 * @param $notificationId
	 * @param $deepLoad
	 * @return unknown_type
	 */
	public function getNotificationById($notificationId, $deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoad();
			
		return toEntity($this->_CI->NotificationModel->find($notificationId));
	}

	/**
	 * Gets notifications by user
	 * 
	 * @param $user
	 * @param $notificationType
	 * @param $deepLoad
	 * @return unknown_type
	 */
	public function getNotificationsByUser($user, $notificationType, $deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoad();
			
		return toEntities($this->_CI->NotificationModel->find_all_by(array("Notifications.userId" => $user->id, "type" => $notificationType)));
	}
	
	/**
	 * Adds the joins to deep load the notification
	 * 
	 * @return unknown_type
	 */
	private function _deepLoad()
	{
		$this->_CI->load->orm("MessageModel");
		$this->_CI->load->orm("BidModel");
		
		$this->_CI->NotificationModel->join_related("user");
		$this->_CI->NotificationModel->join_related("message");
		$this->_CI->NotificationModel->join_related("bid");
	}
}

?>