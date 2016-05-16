<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * A library that deals with google maps
 */
class GoogleMapsLib
{
	const URL = "http://maps.google.com/maps/geo?output=xml&key=";
	const GOOGLE_MAPS_URL = "http://maps.google.com/maps?q=";
	
	private $_CI;
	private $_url;
	
	/**
	 * Constructor
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		$this->_CI =& get_instance();
		$this->_url = GoogleMapsLib::URL . $this->_CI->config->item("google_key");
	}
	
	/**
	 * Gets the lat/long for an address. Also returns a status.
	 * 
	 * @param $address
	 * @throws ConnectionException
	 * @return associative array
	 */
	public function getLatLong($address)
	{
		$request_url = $this->_url . "&q=" . urlencode($address);
		$xml = simplexml_load_file($request_url);
		if($xml === false)
			throw new ConnectionException();
		
		$status = (string)$xml->Response->Status->code;		
		if($status != 200)
			return array("latitude" => 0, "longitude" => 0, "status" => $status);
			
		$coordinates = $xml->Response->Placemark->Point->coordinates;
		$coordinatesSplit = split(",", $coordinates);
		$lat = $coordinatesSplit[1];
      	$lng = $coordinatesSplit[0];
		return array("latitude" => $lat, "longitude" => $lng, "status" => $status);
	}

	/**
	 * Gets the link to google maps for the passed in location
	 * 
	 * @param $location
	 * @return unknown_type
	 */
	public function getGoogleMapLink($location)
	{
		return GoogleMapsLib::GOOGLE_MAPS_URL . $location->street1 . (isset($location->street2) ? " " . $location->street2 : "") . ", " . 
						$location->city . ", " . $location->region . " " . $location->postalCode;
	}
}