<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * A library that deals with landing pages
 */
class LandingPageLib
{
	public $model;
	
	private $_CI;
	
	/**
	 * Constructor
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		$this->_CI =& get_instance();
		$this->_CI->load->orm("LandingPageModel");
		$this->model = $this->_CI->LandingPageModel;
	}
		
	/**
	 * Gets landing pages that are landing pages for categories
	 * 
	 * @param $deepLoad
	 * @return unknown_type
	 */
	public function getCategoryLandingPages($deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoad();
		
		return toEntities($this->_CI->LandingPageModel->find_all_by("isCategoryLanding", '1'));
	}
	
	/**
	 * Gets landing pages that are not landing pages for categories
	 * 
	 * @param $deepLoad
	 * @return unknown_type
	 */
	public function getNonCategoryLandingPages($deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoad();
		
		return toEntities($this->_CI->LandingPageModel->find_all_by("isCategoryLanding", '0'));
	}
	
	/**
	 * Gets a landing page by id
	 * 
	 * @param $id
	 * @param $deepLoad
	 * @return unknown_type
	 */
	public function getLandingPageById($id, $deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoad();
		
		return toEntity($this->_CI->LandingPageModel->find($id));
	}
	
	/**
	 * Gets all the landing pages
	 * 
	 * @return unknown_type
	 */
	public function getLandingPages($deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoad();
		
		return toEntities($this->_CI->LandingPageModel->find_all());
	}
	
	/**
	 * Gets the landing page for a category
	 * 
	 * @param $category
	 * @param $deepLoad
	 * @return unknown_type
	 */
	public function getLandingPageByCategory($category, $deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoad();

		return toEntity($this->_CI->LandingPageModel->find_by(array("categoryId" => $category->id, "isCategoryLanding" => true)));
	}
	
	/**
	 * Adds the joins to deep load the landing page
	 * 
	 * @return unknown_type
	 */
	private function _deepLoad()
	{
		$this->_CI->load->orm("CategoryModel");
		$this->_CI->LandingPageModel->join_related("category");
	}
}

?>