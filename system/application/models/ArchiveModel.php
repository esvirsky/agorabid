<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A model for the Archives table
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class ArchiveModel extends IgnitedRecord
{
	var $table = "Archives";
	var $act_as = array("timestamped" => array("created_at" => "created", "updated_at" => false));
}

?>