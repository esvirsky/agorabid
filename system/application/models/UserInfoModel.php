<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Represents a user info column in the DB. User info
 * is information about the user that has nothing to do with authentication
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class UserInfoModel extends IgnitedRecord
{
	var $table = "UserInfos";
	var $id_col = "userId";
	var $act_as = array("timestamped" => array("created_at" => "created", "updated_at" => false));
}

?>