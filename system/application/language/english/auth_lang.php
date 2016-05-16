<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

$lang['auth_access_denied'] = "Sorry, you don't have access to this page";

$lang['auth_register_success'] = "Thank you for registering!<br /><br />Account activation instructions have been sent to the email address that you provided";

$lang['auth_login_failed'] = "Username or password is incorrect";

$lang['auth_password_change_old_password_error'] = "The old password you entered is incorrect";
$lang['auth_password_change_success'] = "Password changed successfully";

$lang['auth_email_change_success'] = "<b>Email changed successfully</b><br /><br />
Account activation instructions have been sent to your new email address. Please re-login once account activation is complete";

$lang['auth_forgot_username_success'] = "An email with the username has been sent to {email}";
$lang['auth_forgot_username_email_subject'] = "Username reminder";
$lang['auth_forgot_username_email_body'] = "Dear {name},<br /><br />
A username reminder has been requested for your account. If you haven't requested a reminder, please ignore this email.<br /><br />
Your username is <b>{username}</b><br /><br />
Thank you,<br />
{site_name} Customer Service";

$lang['auth_forgot_password_success'] = "Password reset instructions have been sent to the email address that you provided during registration";
$lang['auth_forgot_password_username_not_found'] = "We could not find the username you entered";

$lang['auth_reset_password_success'] = "Password changed successfully";
$lang['auth_reset_password_resetting_error'] = "There was a problem resetting your password, please try again or contact support";
$lang['auth_reset_password_email_subject'] = "Password reset";
$lang['auth_reset_password_email_body'] = "Dear {name},<br /><br />
There was a request to reset the password for this account. If you haven't requested to reset your password, please ignore this email.<br /><br />
Please go to <a href='{password_reset_link}'>{password_reset_link}</a> to create a new password.<br /><br />
Thank you,<br />
{site_name} Customer Service";

$lang['auth_activation_success'] = "Your account has been activated successfully<br /><br />
You can now login using the username and password you chose during registration";
$lang['auth_activation_link_error'] = "There was a problem with the activation link, please contact support";
$lang['auth_activation_code_error'] = "There was a problem activating your account, please contact support";
$lang['auth_activation_missing_error'] = "There was a problem activating your account<br /><br />
If you registered more than a week ago, your account has expired. Please re-register";
$lang['auth_activation_registration_message'] = "Thank you for registering!<br /><br />";
$lang['auth_activation_email_subject'] = "Account activation";
$lang['auth_activation_email_body'] = "Dear {name},<br /><br />
{registration_message}
Your account has to be activated before you can use it. Please go to <a href='{activation_link}'>{activation_link}</a> to activate your account.<br /><br />
Thank you,<br />
{site_name} Customer Service";
