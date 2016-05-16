<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A model for the MessageThreads table
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class MessageThreadModel extends IgnitedRecord
{
	var $table = "MessageThreads";
	var $has_many = array("name" => "messages", "table" => "Messages", "fk" => "messageThreadId");
	var $belongs_to = array(
							array("name" => "user", "table" => "Users", "fk" => "userId"),
							array("name" => "serviceRequest", "table" => "ServiceRequests", "fk" => "serviceRequestId"),
							array("name" => "bid", "table" => "Bids", "fk" => "bidId"),
							);
	
	var $act_as = array("timestamped" => array("created_at" => "created", "updated_at" => false));
}

?>