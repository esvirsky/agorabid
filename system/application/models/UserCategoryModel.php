<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A user to category mapping. A user has a one to many relationship with categories
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class UserCategoryModel extends IgnitedRecord
{
	var $table = "UserCategories";
	var $id_col = array("userId", "categoryId");
	var $belongs_to = array("name" => "category", "table" => "Categories", "fk" => "categoryId");

	/**
	 * Deletes all category mappings to this user
	 * 
	 * @param $user
	 * @return unknown_type
	 */
	public static function deleteByUser($user)
	{
		$CI = & get_instance();
		$CI->load->database();
		if(!$CI->db->query("DELETE FROM UserCategories WHERE userId='$user->id'"))
			error("Couldn't delete user categories");
	}
}

?>