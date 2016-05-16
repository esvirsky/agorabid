<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A model for the Locations table
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class LocationModel extends IgnitedRecord
{
	var $table = "Locations";
	var $act_as = array("timestamped" => array("created_at" => "created", "updated_at" => false));
	
	/**
	 * Deletes all location mappings to this user
	 * 
	 * @param $user
	 * @return unknown_type
	 */
	public static function deleteByUser($user)
	{
		$CI = & get_instance();
		$CI->load->database();
		if(!$CI->db->query("DELETE FROM Locations WHERE userId='$user->id'"))
			error("Couldn't delete user locations");
	}

	/**
	 * Adds geocode information to the location model
	 * 
	 * @param $locationModel
	 * @return unknown_type
	 */
	public static function addGeocode($locationModel)
	{
		$address = $locationModel->street1 . " " . $locationModel->street2 . ", " . 
				$locationModel->city . ", " . $locationModel->region . " " . $locationModel->postalCode . " " . $locationModel->country;
		
		$ar = array();
		$CI = & get_instance();
		$CI->load->library("googlemapslib");
		try
		{	
			$ar = $CI->googlemapslib->getLatLong($address);
		}
		catch(ConnectionException $ex)
		{
			$ar = array("latitude" => 0, "longitude" => 0, "status" => 1);
		}
		
		$changed = !isset($locationModel->latitude) || !isset($locationModel->longitude) || !compareFloats($locationModel->latitude, $ar["latitude"], .001) || !compareFloats($locationModel->longitude, $ar["longitude"], .001);

		$locationModel->geocodeStatus = $ar["status"];
		$locationModel->latitude = $ar["latitude"];
		$locationModel->longitude = $ar["longitude"];

		if($locationModel->geocodeStatus != 200)
		{
			$locationModel->latitude = 0;
			$locationModel->longitude = 0;
			$locationModel->offsetLatitude = 0;
			$locationModel->offsetLongitude = 0;
		}
		else if($changed)
		{
			// add a random offset
			$offset = mt_rand($CI->config->item("location_offset_miles_min")*1000, $CI->config->item("location_offset_miles_max")*1000)/1000.0;
			$direction = mt_rand(0, 360);
	
			$radians = deg2rad($direction);
			$xOffset = $offset * sin($radians);
			$yOffset = $offset * cos($radians);
			
			// convert miles to lat/long
			$milesPerLongitude = ((24900)*cos(deg2rad($locationModel->latitude)))/360;

			$locationModel->offsetLatitude = 1/(69/$yOffset);
			$locationModel->offsetLongitude = 1/($milesPerLongitude/$xOffset);
		}
	}
}

?>