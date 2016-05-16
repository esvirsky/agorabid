<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A model for the ServiceRequests table
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class ServiceRequestModel extends IgnitedRecord
{
	var $table = "ServiceRequests";
	var $has_many = array("name" => "messageThreads", "table" => "MessageThreads", "fk" => "serviceRequestId");
	var $belongs_to = array(
						array("name" => "user", "table" => "Users", "fk" => "userId"),
						array("name" => "location", "table" => "Locations", "fk" => "locationId"),
						array("name" => "category", "table" => "Categories", "fk" => "categoryId")
					);
	var $act_as = array("timestamped" => array("created_at" => "created", "updated_at" => false));
}

?>
