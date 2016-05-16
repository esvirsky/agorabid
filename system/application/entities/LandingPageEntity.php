<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This class represents a landing page
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class LandingPageEntity
{
	/**
	 * Gets the full uri to get to this landing page
	 * 
	 * @return unknown_type
	 */
	public function getUri()
	{
		return "/landing/$this->id/$this->urlName";
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
		if($name == "category")
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