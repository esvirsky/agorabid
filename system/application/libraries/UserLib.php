<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * A library that deals with users.
 * 
 * Note: not authentication related stuff - authentication stuff is in AuthLib
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class UserLib
{
	const LEVEL_BUYER = 1;
	const LEVEL_SELLER = 2;
	
	const SR_NOTIFY_NONE = "none";
	const SR_NOTIFY_ALL = "all";
	const SR_NOTIFY_RADIUS = "radius";
	
	public $model;
	
	private $_CI;
	private $_loggedInUser;
	
	/**
	 * Constructor
	 *
	 * @return UserLib
	 */
	public function __construct()
	{
		$this->_CI =& get_instance();
		$this->model = $this->_CI->UserModel;
	}
	
	/**
	 * Checks that the user is allowed to be on this page. This method uses AuthLib::check(). It
	 * should be used in every controller ( except for the auth controller ) - it is an application
	 * specific user check
	 * 
	 * @param $level
	 * @return unknown_type
	 */
	public function check($level)
	{	
		// user has to be registered and logged in
		$this->_CI->authlib->check(AuthLib::LEVEL_REGISTERED);
		
		$user = $this->getLoggedInUser();

		// check that the user submitted the basic info
		$this->checkUserInfo($user);
		
		// check if the user has a create SR pending
		$this->checkCreateSR($user);
		
		// check that the user has permissions to view this page
		if($user->isBuyer())
			$userLevel = 1;
		else if($user->isSeller())
			$userLevel = 2;
		else
			$userLevel = 0;
			
		if($userLevel < $level)
		{
			redirect("auth/access_denied", "location");
			exit();
		}
		
		return true;
	}

	/**
	 * Checks that the user has submitted all of their info. If not redirects them.
	 * 
	 * @param $user
	 * @return unknown_type
	 */
	public function checkUserInfo($user)
	{
		// All users have to submit their main info
		if(!isset($user->info))
		{
			if(uri_string() == "/user/info")
				return;
			
			redirect('/user/info', 'location');
			exit();
		}

		// We still need more user info
		if(!$user->info->infoComplete)
		{
			//------------------------------------------------------------------------------
			// 1. The user can always go back to /user/info without being redirected
			// 2. The user can go back in the path tree
			//    a. If $user->infoPath == /user/location_manager, then we can go to info and location_manager
			//    b. If $user->infoPath == /user/category_manager, then we can go to info, location_manager, and category_manager
			//------------------------------------------------------------------------------
			$uri = uri_string();
			if($uri == "/user/info" || $uri == "/user/location_manager" || $uri == $user->info->infoPath)
				return;

			redirect($user->info->infoPath, 'location');
			exit();
		}
	}
	
	/**
	 * Checks that the user doesn't have a create service request pending. If he does, then
	 * redirect
	 * 
	 * @return unknown_type
	 */
	public function checkCreateSR($user)
	{
		if(!$user->info->infoComplete)
			return;
		
		if($this->_CI->session->userdata("createServiceRequest") !== false)
		{
			redirect("/service_request/create_confirm", "location");
			exit();
		}
	}
	
	/**
	 * Gets the currently logged in user with user info
	 * 
	 * @param $reload whether to reload, or use cache
	 * @return UserEntity null if not logged in
	 */
	public function getLoggedInUser($reload = false)
	{
		if(false === $this->_CI->session->userdata("userId"))
			return ($this->_loggedInUser = null);
		
		if(isset($this->_loggedInUser) && !$reload)
			return $this->_loggedInUser;
			
		$this->_CI->UserModel->join_related("info");
		return ($this->_loggedInUser = toEntity($this->_CI->UserModel->find($this->_CI->session->userdata("userId"))));
	}

	/**
	 * Gets a user by username
	 * 
	 * @param $username
	 * @return unknown_type
	 */
	public function getUserByUsername($username)
	{
		$this->_CI->UserModel->join_related("info");
		return toEntity($this->_CI->UserModel->find_by("username", $username));
	}
	
	/**
	 * Gets a user by id
	 * 
	 * @param $username
	 * @return unknown_type
	 */
	public function getUserById($userId)
	{
		$this->_CI->UserModel->join_related("info");
		return toEntity($this->_CI->UserModel->find($userId));
	}

	/**
	 * Gets all the users
	 * 
	 * @return unknown_type
	 */
	public function getUsers()
	{
		$this->_CI->UserModel->join_related("info");
		return toEntities($this->_CI->UserModel->find_all());
	}

	/**
	 * Gets users by category
	 * 
	 * @param $category
	 * @return unknown_type
	 */
	public function getUsersByCategory($category)
	{
		$this->_CI->load->orm("UserCategoryModel");
		$this->_CI->UserModel->join_related("category");
		$this->_CI->UserModel->join_related("info");
		return toEntities($this->_CI->UserModel->find_all_by("category.categoryId", $category->id));
	}
	
	/**
	 * Finds users by category and latLongBox
	 * 
	 * @param $category
	 * @param $latLongBox An object with lat/long for each direction
	 * @return unknown_type
	 */
	public function findUsers($category = null, $latLongBox = null)
	{
		$this->_CI->UserModel->join_related("info");
		
		if($category != null)
		{
			$this->_CI->load->orm("UserCategoryModel");
			$this->_CI->UserModel->join_related("category");
			$this->_CI->UserModel->where("category.categoryId", $category->id);
		}

		if($latLongBox != null)
		{
			$this->_CI->load->orm("LocationModel");
			$this->_CI->UserModel->join_related("location");
			$this->_CI->UserModel->where("location.latitude >=", $latLongBox->south, false);
			$this->_CI->UserModel->where("location.latitude <=", $latLongBox->north, false);
			$this->_CI->UserModel->where("location.longitude >=", $latLongBox->west, false);
			$this->_CI->UserModel->where("location.longitude <=", $latLongBox->east, false);
		}
		
		return toEntities($this->_CI->UserModel->find_all());
	}
}

?>