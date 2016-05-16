<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * A library that deals with bids
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class BidLib
{
	public $model;
	
	private $_CI;
	
	/**
	 * Constructor
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		$this->_CI =& get_instance();
		$this->_CI->load->orm("BidModel");
		$this->model = $this->_CI->BidModel;
	}

	/**
	 * Gets a bid by the user and service request
	 * 
	 * @param $user
	 * @param $serviceRequest
	 * @param $deepLoad If true will load all related entities
	 * @return unknown_type
	 */
	public function getBidByUserServiceRequest($user, $serviceRequest, $deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoad();
		
		return toEntity($this->_CI->BidModel->find_by(array("Bids.userId" => $user->id, "Bids.serviceRequestId" => $serviceRequest->id)));
	}
	
	/**
	 * Gets all the bids for a service request
	 * 
	 * @param $serviceRequest
	 * @param $deepLoad If true will load all related entities
	 * @return unknown_type
	 */
	public function getBidsByServiceRequest($serviceRequest, $deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoad();
			
		return toEntities($this->_CI->BidModel->find_all_by("Bids.serviceRequestId", $serviceRequest->id));
	}

	/**
	 * Gets the bid by id
	 * 
	 * @param $bidId
	 * @return unknown_type
	 */
	public function getBidById($bidId, $deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoad();
			
		return toEntity($this->_CI->BidModel->find($bidId));
	}
	
	/**
	 * Gets bids by user
	 * 
	 * @param $user
	 * @param $deepLoad
	 * @return unknown_type
	 */
	public function getBidsByUser($user, $deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoad();
			
		return toEntities($this->_CI->BidModel->find_all_by("Bids.userId", $user->id));
	}
	
	/**
	 * Gets accepted bids that haven't been reviewed by this user yet
	 * 
	 * @param $user
	 * @return unknown_type
	 */
	public function getUnreviewedBidsByUser($user)
	{	
		$query = <<<SQL
SELECT b.*
FROM ServiceRequests sr
JOIN Bids b ON (sr.id = b.serviceRequestId)
LEFT JOIN Reviews r ON (b.id = r.bidId)
WHERE sr.userId = ?
AND b.accepted = '1'
AND r.id IS null
SQL;

		return toEntities($this->_CI->BidModel->find_all_by_sql($query, array($user->id)));
	}
	
	/**
	 * Adds the joins to deep load the bid
	 * 
	 * @return unknown_type
	 */
	private function _deepLoad()
	{
		$this->_CI->load->orm("MessageThreadModel");
		$this->_CI->load->orm("ServiceRequestModel");
		$this->_CI->load->orm("LocationModel");
		
		$this->_CI->BidModel->join_related("user");
		$this->_CI->BidModel->join_related("messageThread");
		$this->_CI->BidModel->join_related("serviceRequest");
		$this->_CI->BidModel->join_related("location");
	}
}

?>