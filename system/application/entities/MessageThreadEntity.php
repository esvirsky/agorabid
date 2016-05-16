<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This class represents a message thread
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class MessageThreadEntity
{
	/**
	 * Called when a property cannot be found
	 * 
	 * @param $name
	 * @return unknown_type
	 */
	public function __get($name)
	{
		$CI =& get_instance();
		// lazy loading info
		if($name == "messages")
		{
			$CI->load->orm("MessageModel");
			return ($this->messages = toEntities($CI->MessageModel->find_all_by("messageThreadId", $this->id)));
		}
		else if($name == "serviceRequest")
		{
			if(!isset($this->serviceRequestId))
				return ($this->serviceRequest = null);
			
			$CI->load->orm("ServiceRequestModel");
			return ($this->serviceRequest = toEntity($CI->ServiceRequestModel->find($this->serviceRequestId)));
		}
		else if($name == "bid")
		{
			if(!isset($this->bidId))
				return ($this->bid = null);
			
			$CI->load->orm("BidModel");
			return ($this->bid = toEntity($CI->BidModel->find($this->bidId)));
		}
		else if($name == "user")
		{
			$CI->UserModel->join_related("info");
			return ($this->user = toEntity($CI->UserModel->find($this->userId)));
		}
	}
}

?>