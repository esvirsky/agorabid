<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A model for the LandingPages table
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class LandingPageModel extends IgnitedRecord
{
	var $table = "LandingPages";
	var $belongs_to = array("name" => "category", "table" => "Categories", "fk" => "categoryId");
}

?>