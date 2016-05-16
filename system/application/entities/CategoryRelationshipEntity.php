<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This class represents a category relationship
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class CategoryRelationshipEntity
{
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
		if($name == "parentId")
		{
			$CI->load->orm("CategoryModel");
			return ($this->parent = toEntity($CI->CategoryModel->find($this->parentId)));
		}
		else if($name == "childId")
		{
			$CI->load->orm("CategoryModel");
			return ($this->child = toEntity($CI->CategoryModel->find($this->childId)));
		}
	}
}

?>