<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A model for the Messages table
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class MessageModel extends IgnitedRecord
{
	var $table = "Messages";
	var $belongs_to = array("name" => "user", "table" => "Users", "fk" => "userId");
	var $act_as = array("timestamped" => array("created_at" => "created", "updated_at" => false));
}

?>