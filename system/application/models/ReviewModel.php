<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A model for the Reviews table
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class ReviewModel extends IgnitedRecord
{
	var $table = "Reviews";
	var $belongs_to = array( 
								array("name" => "user", "table" => "Users", "fk" => "userId"),
								array("name" => "bid", "table" => "Bids", "fk" => "bidId")
							);
	var $act_as = array("timestamped" => array("created_at" => "created", "updated_at" => false));
}

?>