<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A model for the CategoryRelationship table
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class CategoryRelationshipModel extends IgnitedRecord
{
	var $table = "CategoryRelationships";
	var $belongs_to = array( 
								array("name" => "parent", "table" => "Categories", "fk" => "parentId"),
								array("name" => "child", "table" => "Categories", "fk" => "childId")
							);
	var $id_col = array("parentId", "childId");
}

?>