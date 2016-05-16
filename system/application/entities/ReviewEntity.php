<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This class represents a review
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class ReviewEntity
{
	/**
	 * Returns the average rating for a bunch of reviews. Rounds to the nearest whole number
	 * 
	 * @param $reviews
	 * @return Returns -1 if there are no reviews
	 */
	public static function getAverageRating($reviews)
	{
		if(count($reviews) == 0)
			return -1;
		
		$total = 0;
		foreach($reviews as $review)
			$total += $review->rating;
			
		return $total/count($reviews);
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
		if($name == "bid")
		{
			if(!isset($this->bidId))
				return ($this->bid = null);
			
			$CI->load->orm("BidModel");
			return ($this->bid = toEntity($CI->BidModel->find($this->bidId)));
		}
		else if($name == "user")
		{
			if(!isset($this->userId))
				return ($this->user = null);
			
			$CI->UserModel->join_related("info");
			return ($this->user = toEntity($CI->UserModel->find($this->userId)));
		}
		else if($name == "creator")
		{
			$CI->UserModel->join_related("info");
			return ($this->creator = toEntity($CI->UserModel->find($this->creatorId)));
		}
	}
}

?>