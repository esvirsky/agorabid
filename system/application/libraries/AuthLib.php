<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * Authentication library class. Anything that is common to
 * the entire application, not just auth controller is in here.
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class AuthLib
{
	const LEVEL_REGISTERED = 1;
	const REMEMBER_DAYS = 14;
	const REMEMBER_COOKIE = "remember";
	
	private $_CI;
	
	/**
	 * Constructor
	 *
	 * @return AuthLib
	 */
	public function AuthLib()
	{
		$this->_CI =& get_instance();
		$this->_CI->load->orm("UserModel");
		$this->_CI->load->library('session');
	}
	
	/**
	 * Checks if the user is logged in, if not redirects
	 * to the default log in page.
	 * 
	 * To be used at the controller or the method level
	 *
	 * @param int $level the level of the page - one of the LEVEL_* constants
	 */
	public function check($level)
	{
		if($this->isLoggedIn() && $this->_CI->session->userdata("userLevel") >= $level)
			return true;
		
		$this->_CI->load->helper('url');
		redirect('/auth/login' . "?destination=" . uri_string(), 'location');
		exit();
	}
	
	/**
	 * Gets an active user by username
	 *
	 * @param unknown_type $username
	 * @return unknown UserModel
	 */
	public function getActiveUser($username)
	{
		return $this->_CI->UserModel->find_by(array("username" => $username, "activated" => true));
	}
	
	/**
	 * Gets the currently logged in user.
	 * 
	 * @return UserModel
	 */
	public function getLoggedInUser()
	{
		return $this->isLoggedIn() ? $this->_CI->UserModel->find($this->_CI->session->userdata("userId")) : null;
	}
	
	/**
	 * Gets the user from the remember me cookie
	 * 
	 * @return UserModel if there is a remember me user; null otherwise
	 */
	public function getRememberMeUser()
	{
		$this->_CI->load->helper('cookie');
		$value = get_cookie(AuthLib::REMEMBER_COOKIE);
		if($value !== false)
		{
			$parts = explode(":", $value);
			if(count($parts) == 2)
			{
				$username = $parts[0];
				$code = $parts[1];
				$user = $this->getActiveUser($username);
				if($user !== false && $user->rememberCode == $code && strtotime($user->rememberExpire) > time())
					return $user;
			}
		}
		return null;
	}
	
	/**
	 * Is the user logged in - with any level
	 * 
	 * If remember me is set, logs the user in.
	 */
	public function isLoggedIn()
	{
		$userId = $this->_CI->session->userdata("userId");
		if($userId !== false)
			return true;
		
		$user = $this->getRememberMeUser();	
		if($user != null)
		{
			$this->_CI->session->set_userdata("userId", $user->id);
			$this->_CI->session->set_userdata("userLevel", AuthLib::LEVEL_REGISTERED);
			return true;
		}
		
		return false;
	}
	
	/**
	 * Logout the user
	 */
	public function logout()
	{
		$this->_CI->load->helper('cookie');
		delete_cookie(AuthLib::REMEMBER_COOKIE);
	
		$user = $this->getLoggedInUser();
		if($user->rememberCode != null || $user->rememberExpire != null)
		{
			$user->__no_escape_data = array("rememberCode" => "NULL", "rememberExpire" => "NULL");
			$user->save() or error();
		}

		$this->_CI->session->unset_userdata("userId");
		$this->_CI->session->unset_userdata("userLevel");
	}	
}

?>