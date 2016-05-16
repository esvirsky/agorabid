<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * A library that deals with reviews
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class ReviewLib
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
		$this->_CI->load->orm("ReviewModel");
	}
	
	/**
	 * Gets reviews by user - includes reviews for user's bids
	 * 
	 * @param $user
	 * @return unknown_type
	 */
	public function getReviewsByUser($user)
	{
		$this->_CI->load->orm("BidModel");
		$this->_CI->ReviewModel->join_related("bid");		
		return toEntities($this->_CI->ReviewModel->where("Reviews.userId", $user->id)->or_where("bid.userId", $user->id)->find_all());
	}
	
	/**
	 * Can this user review this bid
	 * 
	 * @param $user
	 * @param $bid
	 * @return unknown_type
	 */
	public function canReviewBid($user, $bid)
	{
$query = <<<SQL
SELECT count(*) as count
FROM ServiceRequests sr
JOIN Bids b ON (sr.id = b.serviceRequestId)
LEFT JOIN Reviews r ON (b.id = r.bidId)
WHERE sr.userId = ?
AND b.id = ?
AND b.accepted = '1'
AND r.id IS null
SQL;
	
		$this->_CI->load->database();
		$result = $this->_CI->db->query($query, array($user->id, $bid->id));
		if($result === false)
			error("Couldn't run a query");

		return $result->row()->count > 0;
	}

	/**
	 * Has this user already reviewed this bid
	 * 
	 * @param $user
	 * @param $bid
	 * @return unknown_type
	 */
	public function hasReviewedBid($user, $bid)
	{
		return $this->_CI->ReviewModel->where(array("creatorId" => $user->id, "bidId" => $bid->id))->count() > 0;
	}
}

?>