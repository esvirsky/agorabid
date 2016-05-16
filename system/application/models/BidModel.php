<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A model for the Bids table
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class BidModel extends IgnitedRecord
{
	var $table = "Bids";
	var $belongs_to = array( 
								array("name" => "user", "table" => "Users", "fk" => "userId"),
								array("name" => "location", "table" => "Locations", "fk" => "locationId"),
								array("name" => "serviceRequest", "table" => "ServiceRequests", "fk" => "serviceRequestId")
							);
							
	var $has_one = array("name" => "messageThread", "table" => "MessageThreads", "fk" => "bidId");
	var $act_as = array("timestamped" => array("created_at" => "created", "updated_at" => false));
}

?>