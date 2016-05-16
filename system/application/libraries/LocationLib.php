<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * A library that deals with locations
 */
class LocationLib
{
	private $_CI;
	
	/**
	 * Constructor
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		$this->_CI =& get_instance();
		$this->_CI->load->orm("LocationModel");
	}
	
	/**
	 * Determines if this location can be deleted. Can be
	 * deleted if it doesn't have any service requests or
	 * bids associated to it.
	 * 
	 * @param $location
	 * @return unknown_type
	 */
	public function canDeleteLocation($location)
	{
		$this->_CI->load->orm("ServiceRequestModel");
		$this->_CI->load->orm("BidModel");
		
		$srs = $this->_CI->ServiceRequestModel->where("locationId", $location->id)->count();
		$bids = $this->_CI->BidModel->where("locationId", $location->id)->count();
		return empty($srs) && empty($bids);
	}
	
	/**
	 * Counts locations by user
	 * 
	 * @param $user
	 * @return unknown_type
	 */
	public function countLocationsByUser($user)
	{
		return $this->_CI->LocationModel->where("userId", $user->id)->count();
	}
	
	/**
	 * Gets locations by user
	 * 
	 * @param $user
	 * @return unknown_type
	 */
	public function getLocationsByUser($user)
	{
		return toEntities($this->_CI->LocationModel->find_all_by("userId", $user->id));
	}
	
	/**
	 * Gets location by id
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	public function getLocationById($id)
	{
		return toEntity($this->_CI->LocationModel->find($id));
	}
}

?>