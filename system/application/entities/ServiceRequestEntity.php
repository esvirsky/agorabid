<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This class represents a service request
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class ServiceRequestEntity
{
	public function isOpen()
	{
		return $this->status == ServiceRequestLib::STATUS_OPEN;
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
		if($name == "user")
		{
			$CI->UserModel->join_related("info");
			return ($this->user = toEntity($CI->UserModel->find($this->userId)));
		}
		else if($name == "category")
		{
			$CI->load->orm("CategoryModel");
			return ($this->category = toEntity($CI->CategoryModel->find($this->categoryId)));
		}
		else if($name == "location")
		{
			$CI->load->orm("LocationModel");
			return ($this->location = toEntity($CI->LocationModel->find($this->locationId)));
		}
	}
}

?>
