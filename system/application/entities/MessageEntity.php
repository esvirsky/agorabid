<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This class represents a message
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class MessageEntity
{
	/**
	 * Gets the service request that this message belongs to
	 * 
	 * @return unknown_type
	 */
	public function getServiceRequest()
	{
		$messageThread = $this->messageThread;
		return $messageThread->serviceRequest == null ? $messageThread->bid->serviceRequest : $messageThread->serviceRequest;
	}
	
	/**
	 * Called when a property cannot be found
	 * 
	 * @param $name
	 * @return unknown_type
	 */
	public function __get($name)
	{
		$CI =& get_instance();
		// lazy loading
		if($name == "messageThread")
		{
			$CI->load->orm("MessageThreadModel");
			return ($this->messageThread = toEntity($CI->MessageThreadModel->find($this->messageThreadId)));
		}
		else if($name == "recipient")
		{
			$CI->UserModel->join_related("info");
			return ($this->recipient = toEntity($CI->UserModel->find($this->recipientId)));
		}
		else if($name == "sender")
		{
			$CI->UserModel->join_related("info");
			return ($this->sender = toEntity($CI->UserModel->find($this->senderId)));
		}
	}
}

?>