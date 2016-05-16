<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * ServiceRequest library
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class ServiceRequestLib
{
	const STATUS_OPEN = "open";
	const STATUS_CLOSED = "closed";
	
	public $model;
	
	private $_CI;
	
	/**
	 * Constructor
	 *
	 * @return AuthLib
	 */
	public function __construct()
	{
		$this->_CI =& get_instance();
		$this->_CI->load->orm("ServiceRequestModel");
		$this->model = $this->_CI->ServiceRequestModel;
	}

	/**
	 * Determines if this SR can be deleted. Can be delete if it doesn't have any bids or messages
	 * associated to it.
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	public function canDeleteServiceRequest($serviceRequest)
	{
		$this->_CI->load->orm("MessageThreadModel");
		$this->_CI->load->orm("BidModel");
		$messageThreads = $this->_CI->MessageThreadModel->where("serviceRequestId", $serviceRequest->id)->count();
		$bids = $this->_CI->BidModel->where("serviceRequestId", $serviceRequest->id)->count();
		return empty($messageThreads) && empty($bids);
	}
	
	/**
	 * Gets a service request by id
	 * 
	 * @param $id
	 * @param $deepLoad If true will load all related entities
	 * @return unknown_type ServiceRequestEntity
	 */
	public function getServiceRequestById($id, $deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoad();
		
		return toEntity($this->_CI->ServiceRequestModel->find($id));
	}
	
	/**
	 * Gets service requests by user
	 * @param $user
	 * @param $deepLoad
	 * @return unknown_type
	 */
	public function getServiceRequestsByUser($user, $deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoad();
		
		return toEntities($this->_CI->ServiceRequestModel->find_all_by("ServiceRequests.userId", $user->id));
	}

	/**
	 * Gets service requests by a category.
	 * 
	 * @param $categoryId
	 * @param $nested If true will also retun the SRs that have categories that are children of the passed in category - only 1 level deep
	 * @param $deepLoad
	 * @return unknown_type
	 */
	public function getServiceRequestsByCategory($category, $nested = true, $deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoad();
		
		if(!$nested)
			return toEntities($this->_CI->ServiceRequestModel->find_all_by("ServiceRequests.categoryId", $category->id));	
					
		$this->_CI->load->library("categorylib");
		$map = $this->_CI->categorylib->getParentMap();
		$categories = isset($map[$category->id]) ? getObjectProperties($map[$category->id], "id") : array();
		$categories[] = $category->id;
		
		return toEntities($this->_CI->ServiceRequestModel->where_in("ServiceRequests.categoryId", $categories)->find_all());
	}
	
	/**
	 * Gets service requests by a category withing a certain radius of lat/long
	 * 
	 * Will return service requests that have categories which are children of the passed in category,
	 * works only 1 level deep.
	 * 
	 * @param $category
	 * @param $lat
	 * @param $long
	 * @param $radius
	 * @return unknown_type
	 */
	public function getServiceRequestsByCategoryRadius($category, $lat, $long, $radius, $deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoad();
		else
		{
			$this->_CI->load->orm("LocationModel");
			$this->_CI->ServiceRequestModel->join_related("location");
		}
		
		$milesPerLat = 69;
		$milesPerLong = ((24900)*cos(deg2rad($lat)))/360;
		$latPerMile = 1/$milesPerLat;
		$longPerMile = 1/$milesPerLong;

		$minLat = $lat - ($latPerMile*$radius);
		$maxLat = $lat + ($latPerMile*$radius);
		$minLong = $long - ($longPerMile*$radius);
		$maxLong = $long + ($longPerMile*$radius);

		$area = array( "location.latitude >=" => $minLat,
						"location.latitude <=" => $maxLat,
						"location.longitude >=" => $minLong,
						"location.longitude <=" => $maxLong );
		
		$this->_CI->load->library("categorylib");
		$map = $this->_CI->categorylib->getParentMap();
		$categories = isset($map[$category->id]) ? getObjectProperties($map[$category->id], "id") : array();
		$categories[] = $category->id;
		
		$srModels = $this->_CI->ServiceRequestModel->where_in("ServiceRequests.categoryId", $categories)->find_all_by($area);
		$serviceRequests = array();

		foreach($srModels as $srModel)
		{
			$offsetLat = $lat - $srModel->location->latitude;
			$offsetLong = $long - $srModel->location->longitude;
			$offsetY = $offsetLat * $milesPerLat;
			$offsetX = $offsetLong * $milesPerLong;

			if(sqrt($offsetY*$offsetY + $offsetX*$offsetX) <= $radius)
				$serviceRequests[] = toEntity($srModel);
		}

		return $serviceRequests;
	}
		
	/**
	 * Saves a service request entity to the database
	 * 
	 * @param $serviceRequest ServiceRequestEntity
	 * @return ServiceRequestModel
	 */
	public function saveServiceRequest($serviceRequest)
	{
		$this->_CI->load->orm("LocationModel");
		
		// Archive a copy of the service request, if one is being modified
		if(isset($serviceRequest->id))
		{
			$srModel = $this->_CI->ServiceRequestModel->find($serviceRequest->id);
			$this->_CI->load->orm("ArchiveModel");
			$archive = $this->_CI->ArchiveModel->new_record();
			$archive->id = $serviceRequest->id;
			$archive->type = "service_request";
			$archive->object = json_encode($srModel->get_data());
			$archive->save() or error();
		}
		
		$locationModel = isset($serviceRequest->locationId) ? $this->_CI->LocationModel->find($serviceRequest->locationId) : $this->_CI->LocationModel->new_record();
		mapEntityToModel($locationModel, $serviceRequest->location);
		$locationModel->save() or error();
		
		$srModel = isset($serviceRequest->id) ? $this->_CI->ServiceRequestModel->find($serviceRequest->id) : $this->_CI->ServiceRequestModel->new_record();
		mapEntityToModel($srModel, $serviceRequest);
		$srModel->locationId = $locationModel->uid();
		$srModel->save() or error();
		
		return $srModel;
	}
	
	/**
	 * Finds services requests
	 * 
	 * @param $categories Mixed - can be an array or a single category
	 * @param $latLongBox
	 * @param $deepLoad
	 * @return unknown_type
	 */
	public function findServiceRequestsBox($categories = null, $latLongBox = null, $deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoad();
		
		if($categories != null)
		{
			$categoryIds = is_array($categories) ? getObjectProperties($categories, "id") : array($category->id);
			$this->_CI->ServiceRequestModel->where_in("ServiceRequests.categoryId", $categoryIds);
		}

		if($latLongBox != null)
		{
			$this->_CI->load->orm("LocationModel");
			$this->_CI->ServiceRequestModel->join_related("location");
			$this->_CI->ServiceRequestModel->where("location.latitude >=", $latLongBox->south, false);
			$this->_CI->ServiceRequestModel->where("location.latitude <=", $latLongBox->north, false);
			$this->_CI->ServiceRequestModel->where("location.longitude >=", $latLongBox->west, false);
			$this->_CI->ServiceRequestModel->where("location.longitude <=", $latLongBox->east, false);
		}
		
		return toEntities($this->_CI->ServiceRequestModel->find_all());
	}
	
	/**
	 * Adds the joins to deep load the service request
	 * 
	 * @return unknown_type
	 */
	private function _deepLoad()
	{
		$this->_CI->load->orm("LocationModel");
		$this->_CI->load->orm("CategoryModel");
		$this->_CI->load->orm("MessageThreadModel");
		
		$this->_CI->ServiceRequestModel->join_related("location");
		$this->_CI->ServiceRequestModel->join_related("category");
		$this->_CI->ServiceRequestModel->join_related("user");
		$this->_CI->ServiceRequestModel->join_related("messageThreads");
	}
}

?>