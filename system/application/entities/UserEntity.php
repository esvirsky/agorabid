<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This class represents a user
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class UserEntity
{
	/**
	 * Determines if the user is a buyer
	 * 
	 * @return unknown_type
	 */
	public function isBuyer()
	{
		return $this->info->userType == "buyer";
	}
	
	/**
	 * Determines if the user is a seller
	 * 
	 * @return unknown_type
	 */
	public function isSeller()
	{
		return $this->info->userType == "seller";
	}
	
	/**
	 * Gets the average rating out of 10 for this user
	 * 
	 * @return If there are no reviews, returns a -1
	 */
	public function getAvgRating()
	{
		return ReviewEntity::getAverageRating($this->reviews);
	}
	
	/**
	 * Determines if the user has any categories
	 * 
	 * @return unknown_type
	 */
	public function hasCategories()
	{
		if(isset($this->categories))
			return count($this->categories) > 0;
			
		$this->_CI->load->library("categorylib");
		return $this->_CI->categorylib->countCategoriesByUser($this) > 0;
	}
	
	/**
	 * Determines if the user has any locations
	 * 
	 * @return unknown_type
	 */
	public function hasLocations()
	{
		if(isset($this->locations))
			return count($this->locations) > 0;
		
		$this->_CI->load->library("locationlib");
		return $this->_CI->locationlib->countLocationsByUser($this) > 0;
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
		if($name == "info")
			return ($this->info = toEntity($CI->UserInfoModel->find_by("userId", $this->id)));
		else if($name == "reviews")
		{
			$CI->load->library("reviewlib");
			return ($this->reviews = $CI->reviewlib->getReviewsByUser($this));
		}
		else if($name == "categories")
		{
			$CI->load->library("categorylib");
			return ($this->categories = $CI->categorylib->getCategoriesByUser($this));
		}
		else if($name == "locations")
		{
			$CI->load->library("locationlib");
			return ($this->locations = $CI->locationlib->getLocationsByUser($this));
		}
	}
}

?>