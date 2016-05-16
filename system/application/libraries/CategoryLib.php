<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * A library that deals with categories
 */
class CategoryLib
{
	const CATEGORY_TREE_CACHE_KEY = "category_tree_cache_key";
	const CATEGORY_PARENT_MAP_CACHE_KEY = "category_parent_map_cache_key";
	const CATEGORY_CHILD_MAP_CACHE_KEY = "category_child_map_cache_key";
	
	private $_CI;
	
	/**
	 * Constructor
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		$this->_CI =& get_instance();
		$this->_CI->load->orm("CategoryModel");
	}
	
	/**
	 * Counts categories by user
	 * 
	 * @param $user
	 * @return unknown_type
	 */
	public function countCategoriesByUser($user)
	{
		$this->_CI->load->orm("UserCategoryModel");
		return $this->_CI->UserCategoryModel->where("userId", $user->id)->count();
	}
	
	/**
	 * Gets an array that has the top levels of the category tree. Each node can have a children
	 * property that has the children categories. All arrays are hashed by display name.
	 * 
	 * 
	 * array(
	 * 	C1 display name = C1, 
	 * 		id = 1, 
	 * 		children = array(	"C2DisplayName" => 	C2->name = C2,
	 * 													id = 2,
	 * 													children = ....
	 * 
	 * NOTE: gets cached, so if you change the category tree in DB, clear the cache.
	 * 
	 * @return an array of the top level categories, CategoryEntity
	 */
	public function getCategoryTree()
	{
		$this->_CI->load->library('CacheLib');
		$this->_CI->load->orm("CategoryRelationshipModel");
		
		if($this->_CI->cachelib->existsCache(self::CATEGORY_TREE_CACHE_KEY))
			return $this->_CI->cachelib->getCache(self::CATEGORY_TREE_CACHE_KEY);
	
		$this->_CI->CategoryModel->join_related("children");
		$categories = toEntities($this->_CI->CategoryModel->find_all());

		// Run through each category and index them into a flat array.
		// Top parent is used to select which categories will be at the top
		// of the array
		$index = array();
		$topParent = array();
		foreach($categories as $category)
		{
			$index[$category->id] = $category;
			$topParent[$category->id] = true;
		}
		
		
		// Run through the categories again and build up the tree
		foreach($categories as $category)
		{
			$category->tmpChildren = isset($category->children) ? $category->children : array();
			$category->children = array();

			for($i=0; $i<count($category->tmpChildren); $i++)
			{
				$relationship = $category->tmpChildren[$i];
				$topParent[$relationship->childId] = false;
				$childName = isset($relationship->childDisplayName) ? $relationship->childDisplayName : $index[$relationship->childId]->name;
				$category->children[$childName] = $index[$relationship->childId];
			}
		}
		
		// Run through the categories again and decide which categories
		// appear at the top of the tree
		$topCategories = array();
		foreach($categories as $category)
		{
			unset($category->tmpChildren);
			if($topParent[$category->id])
				$topCategories[$category->name] = $category;
		}
		
		// Run through all the children and sorts them, putting "Other" at the end
		foreach($topCategories as $parentName => $parent)
		{
			if(empty($parent->children))
				continue;
			
			ksort($parent->children);
			
			$other = $parent->children["Other"];
			unset($parent->children["Other"]);
			$parent->children["Other"] = $other;
		}

		$this->_CI->cachelib->setCache(self::CATEGORY_TREE_CACHE_KEY, $topCategories);
		return $topCategories;
	}
	
	/**
	 * Gets a child to parent map
	 * 
	 * Categories without parents are not listed here
	 * 
	 * @return an associative array, with childId => parent
	 */
	public function getChildMap()
	{
		$this->_CI->load->library('CacheLib');
		if($this->_CI->cachelib->existsCache(self::CATEGORY_CHILD_MAP_CACHE_KEY))
			return $this->_CI->cachelib->getCache(self::CATEGORY_CHILD_MAP_CACHE_KEY);
		
		$this->_CI->load->orm("CategoryRelationshipModel");
		$this->_CI->CategoryRelationshipModel->join_related("parent");
		$relationships = $this->_CI->CategoryRelationshipModel->find_all();
		
		$map = array();
		foreach($relationships as $relationship)
			$map[$relationship->childId] = toEntity($relationship->parent);
	
		$this->_CI->cachelib->setCache(self::CATEGORY_CHILD_MAP_CACHE_KEY, $map);
		return $map;
	}
	
	/**
	 * Gets a parent to children map
	 * 
	 * Categories that don't have any children are not listed here
	 * 
	 * @return an associative array, with parentId => array(child1, child2)
	 */
	public function getParentMap()
	{
		$this->_CI->load->library('CacheLib');
		if($this->_CI->cachelib->existsCache(self::CATEGORY_PARENT_MAP_CACHE_KEY))
			return $this->_CI->cachelib->getCache(self::CATEGORY_PARENT_MAP_CACHE_KEY);
		
		$this->_CI->load->orm("CategoryRelationshipModel");
		$this->_CI->CategoryRelationshipModel->join_related("child");
		$relationships = $this->_CI->CategoryRelationshipModel->find_all();
		
		$map = array();
		foreach($relationships as $relationship)
		{
			if(!isset($map[$relationship->parentId]))
				$map[$relationship->parentId] = array();
			
			$map[$relationship->parentId][] = toEntity($relationship->child);
		}
	
		$this->_CI->cachelib->setCache(self::CATEGORY_PARENT_MAP_CACHE_KEY, $map);
		return $map;
	}
	
	/**
	 * Gets the categories by user
	 * 
	 * @param $user
	 * @return unknown_type
	 */
	public function getCategoriesByUser($user)
	{
		$this->_CI->load->orm("UserCategoryModel");
		$this->_CI->UserCategoryModel->join_related("category");
		$userCategories = toEntities($this->_CI->UserCategoryModel->find_all_by("userId", $user->id));
		
		$categories = array();
		foreach($userCategories as $userCategory)
			$categories[] = $userCategory->category;
			
		return $categories;
	}
	
	/**
	 * Gets the category by id
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	public function getCategoryById($id)
	{
		return toEntity($this->_CI->CategoryModel->find($id));
	}
	
	/**
	 * Gets the category by name
	 * 
	 * @param $name
	 * @return unknown_type
	 */
	public function getCategoryByName($name)
	{
		return toEntity($this->_CI->CategoryModel->find_by("name", $name));
	}
	
	/**
	 * Returns all the categories
	 * 
	 * @return unknown_type
	 */
	public function getCategories()
	{
		return toEntities($this->_CI->CategoryModel->find_all());
	}

	/**
	 * Gets the top level categories
	 * 
	 * @return unknown_type
	 */
	public function getTopCategories()
	{
		$top = array();
		foreach($this->getCategoryTree() as $category)
			$top[] = $category;
	
		return $top;
	}
}

?>