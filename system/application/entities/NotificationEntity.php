<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This class represents a notification
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class NotificationEntity
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
		// lazy loading
		if($name == "bid")
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
		else if($name == "message")
		{
			if(!isset($this->messageId))
				return ($this->message = null);
			
			$CI->load->orm("MessageModel");
			return ($this->message = toEntity($CI->MessageModel->find($this->messageId)));
		}
	}
}

?>