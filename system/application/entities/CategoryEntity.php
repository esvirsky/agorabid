<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This class represents a Category
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class CategoryEntity
{
	public function getChildren()
	{
		$CI =& get_instance();
		$CI->load->library("categorylib");
		$map = $CI->categorylib->getParentMap();
		return isset($map[$this->id]) ? $map[$this->id] : array();
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
		if($name == "landingPage")
		{
			$CI->load->library("LandingPageLib");
			return ($this->serviceRequest = $CI->landingpagelib->getLandingPageByCategory($this));
		}
	}	
}

?>