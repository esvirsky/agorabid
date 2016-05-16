<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

$lang['script_review_notification_email_subject'] = "Please review your recent transaction";
$lang['script_sr_notification_email_subject'] = "New service requests notification";

$lang['script_review_notification_email_body'] = "Please help make our site better by reviewing the user <b>{seller}</b> on their recent service for "
	."<b>{sr_title}</b> at this url:<br/><br/><a href='{review_link}'>{review_link}</a>";
$lang['script_sr_notification_email_body'] = "Based on your notification settings, we think that you might be interested in these new service requests:<br/><br/>{sr_list}";