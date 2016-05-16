<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This class represents a bid
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class BidEntity
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
		if($name == "serviceRequest")
		{
			$CI->load->orm("ServiceRequestModel");
			return ($this->serviceRequest = toEntity($CI->ServiceRequestModel->find($this->serviceRequestId)));
		}
		else if($name == "user")
		{
			$CI->UserModel->join_related("info");
			return ($this->user = toEntity($CI->UserModel->find($this->userId)));
		}
		else if($name == "location")
		{
			$CI->load->orm("LocationModel");
			return ($this->location = toEntity($CI->LocationModel->find($this->locationId)));
		}
	}
}

?>