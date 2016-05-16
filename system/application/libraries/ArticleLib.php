<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * A library that deals with articles
 */
class ArticleLib
{
	private $_CI;
	
	/**
	 * Constructor
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		$this->_CI =& get_instance();
		$this->_CI->load->orm("ArticleModel");
	}

	/**
	 * Gets an article by id
	 * 
	 * @param $id
	 * @param $deepLoad
	 * @return unknown_type
	 */
	public function getArticleById($id, $deepLoad = false)
	{
		if($deepLoad)
			$this->_deepLoad();
		
		return toEntity($this->_CI->ArticleModel->find($id));
	}
	
	/**
	 * Adds the joins to deep load the landing page
	 * 
	 * @return unknown_type
	 */
	private function _deepLoad()
	{
	}
}

?>