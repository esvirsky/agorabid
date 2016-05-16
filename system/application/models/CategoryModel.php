<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A model for the Category table
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class CategoryModel extends IgnitedRecord
{
	var $table = "Categories";
	var $has_one = array("name" => "landingPage", "table" => "LandingPages", "fk" => "categoryId");
	var $has_many = array("name" => "children", "table" => "CategoryRelationships", "fk" => "parentId");
}

?>