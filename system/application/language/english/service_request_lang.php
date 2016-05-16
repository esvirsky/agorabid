<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

$lang['sr_only_one_bid'] = "You can only submit one bid for a service request";
$lang['sr_saved'] = "Service request saved successfully";
$lang['sr_created'] = "Service request created successfully";
$lang['sr_deleted'] = "Service request deleted successfully";
$lang['sr_closed'] = "Service request bidding closed";
$lang['sr_accepted_bid'] = "You accepted the bid. You can now finalize the arrangements and receive your service.";
$lang['sr_bid_created'] = "Bid submitted";
$lang['sr_message_created'] = "Message sent";
$lang['sr_reopened'] = "Service request re-opened for bidding";
$lang['sr_create_login'] = "Please log in to finish creating your service request. If you haven't registered, please do so. Your request will be waiting for you inside.";
$lang['sr_create_confirm'] = "Before you logged in, you started creating this service request:<br/><br/>
								Title - <b>{title}</b><br/>Category - <b>{category}</b><br/>Description - <b>{description}</b><br/><br/><br/>
								Do you want to create it now?";

$lang['sr_new_message_email_subject'] = "New message notification";
$lang['sr_new_bid_email_subject'] = "New bid notification";
$lang['sr_bid_accepted_email_subject'] = "Bid accepted notification";

$lang['sr_new_message_email_body'] = "You have a new message from {message_creator}<br /><br />\"{message}\"<br/><br/>Please log into your account to get more information";
$lang['sr_new_bid_email_body'] = "You have a new bid from {bid_creator} for {sr_title}<br/><br/>Please log into your account to get more information";
$lang['sr_bid_accepted_email_body'] = "Your bid for <b>{sr_title}</b> has been accepted. You should now agree on a time and complete the service.
<br/><br/>Please log into your account to get more information";

$lang['sr_notification_email_body'] = "Dear {name},<br /><br />
{message}
<br/><br/>
Thank you,<br />
{site_name} Customer Service
<br /><hr/>If you don't want to receive these notifications anymore please change your notification settings at <a href='{base_url}user/notification_manager'>{base_url}user/notification_manager</a>";