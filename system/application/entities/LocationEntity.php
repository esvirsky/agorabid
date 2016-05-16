<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This class represents a location
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class LocationEntity
{
	/**
	 * Determines if this location has a lat/long
	 * 
	 * @return unknown_type
	 */
	public function hasLatLong()
	{
		return $this->latitude != 0 || $this->longitude != 0;
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
			if(!isset($this->userId))
				return ($this->user = null);
			
			$CI->UserModel->join_related("info");
			return ($this->user = toEntity($CI->UserModel->find($this->userId)));
		}
	}
}

?>