<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Common helper functions. This file gets loaded every time. Can put
 * any required loading in here.
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */

if(!function_exists('milesToLatitude'))
{
	/**
	 * Converts miles to latitude degrees float
	 *
	 * @param unknown_type $miles
	 */
	function milesToLatitude($miles)
	{
		$latPerMile = 1/69;
		return $miles * $latPerMile;
	}
}

if(!function_exists('milesToLongitude'))
{
	/**
	 * Converts miles to longitude degrees float
	 *
	 * @param unknown_type $miles
	 * @param unknown_type $latitude The latitude at which the conversion is to be done at
	 */
	function milesToLongitude($miles, $latitude)
	{
		$longPerMile = 1/(((24900)*cos(deg2rad($latitude)))/360);
		return $miles * $longPerMile;
	}
}