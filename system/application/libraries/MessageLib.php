<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * A library that deals with messages
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class MessageLib
{
	public $messageModel;
	public $messageThreadModel;
	
	private $_CI;
	
	/**
	 * Constructor
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		$this->_CI =& get_instance();
		$this->_CI->load->orm("MessageModel");
		$this->_CI->load->orm("MessageThreadModel");
		$this->messageModel = $this->_CI->MessageModel;
		$this->messageThreadModel = $this->_CI->MessageThreadModel;
	}

	/**
	 * Gets the message thread for this service request and user
	 * 
	 * @param $user
	 * @param $serviceRequest
	 * @param $deepLoad If true will load all related entities
	 * @return unknown_type
	 */
	public function getMessageThreadByUserServiceRequest($user, $serviceRequest, $deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoadThread();
		
		return toEntity($this->_CI->MessageThreadModel->find_by(array("MessageThreads.userId" => $user->id, "MessageThreads.serviceRequestId" => $serviceRequest->id)));
	}
	
	/**
	 * Gets the message thread for this bid
	 * 
	 * @param $bid
	 * @param $deepLoad
	 * @return unknown_type
	 */
	public function getMessageThreadByBid($bid, $deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoadThread();
		
		return toEntity($this->_CI->MessageThreadModel->find_by(array("MessageThreads.bidId" => $bid->id)));
	}
	
	/**
	 * Gets the message thread by Id
	 * 
	 * @param $id
	 * @param $deepLoad
	 * @return unknown_type
	 */
	public function getMessageThreadById($id, $deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoadThread();
		
		return toEntity($this->_CI->MessageThreadModel->find($id));
	}
	
	/**
	 * Returns messages by message thread
	 * 
	 * @param $messageThread
	 * @return unknown_type
	 */
	public function getMessagesByMessageThread($messageThread)
	{
		return toEntities($this->_CI->MessageModel->find_all_by("messageThreadId", $messageThread->id));
	}
	
	/**
	 * Adds the joins to deep load the message thread
	 * 
	 * @return unknown_type
	 */
	private function _deepLoadThread()
	{
		$this->_CI->load->orm("ServiceRequestModel");
		$this->_CI->load->orm("BidModel");
		
		$this->_CI->MessageThreadModel->join_related("user");
		$this->_CI->MessageThreadModel->join_related("serviceRequest");
		$this->_CI->MessageThreadModel->join_related("bid");
		$this->_CI->MessageThreadModel->join_related("messages");
	}
}

?>