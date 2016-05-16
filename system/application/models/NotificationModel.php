<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A model for the Notifications table
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class NotificationModel extends IgnitedRecord
{
	var $table = "Notifications";
	var $belongs_to = array( 
								array("name" => "user", "table" => "Users", "fk" => "userId"),
								array("name" => "bid", "table" => "Bids", "fk" => "bidId"),
								array("name" => "message", "table" => "Messages", "fk" => "messageId")
							);
	
	var $act_as = array("timestamped" => array("created_at" => "created", "updated_at" => false));
}

?>