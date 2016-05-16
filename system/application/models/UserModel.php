<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Represents a user in the database system
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class UserModel extends IgnitedRecord
{
	const EXPIRATION_DAYS = 7;

	var $table = "Users";
	var $has_one = array("name" => "info", "table" => "UserInfos", "fk" => "userId");
	var $has_many = array(
						array("name" => "category", "table" => "UserCategories", "fk" => "userId"),
						array("name" => "location", "table" => "Locations", "fk" => "userId")
					);
	var $act_as = array("timestamped" => array("created_at" => "created", "updated_at" => false));
	
	/**
	 * Deletes all expired inactive users - users that didn't activate their account and expired
	 */
	public static function deleteExpired()
	{
		$CI = & get_instance();
		$CI->load->database();
		if(!$CI->db->query("DELETE FROM Users WHERE DATE_SUB(NOW(), INTERVAL " . UserModel::EXPIRATION_DAYS . " DAY) > created AND activated='0'"))
			log_message("error", "Couldn't garbage collect Users table");
	}
}

?>