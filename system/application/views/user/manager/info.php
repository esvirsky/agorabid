<div id="divInfoManagerToggle" class="Foldout"><a name="info_settings"><img src="/images/collapsed.gif" /> <span>My Info/Settings</span></a></div>
<div id="divInfoManager" class="SectionBox">
	<div><img src="/images/bullet1.png"/> <a href="/auth/change_email">Change email</a></div>
	<div><img src="/images/bullet1.png" /> <a href="/auth/change_password">Change password</a></div>
	<div><img src="/images/bullet1.png" /> <a href="/user/info">Change my info</a></div>
	<?php if($this->userlib->getLoggedInUser()->isSeller()) { ?>
		<div><img src="/images/bullet1.png" /> <a href="/user/location_manager">Manage my locations</a></div>
		<div><img src="/images/bullet1.png" /> <a href="/user/category_manager">Manage my categories</a></div>
	<?php } ?>
	<div><img src="/images/bullet1.png" /> <a href="/user/notification_manager">Manage my email notifications</a></div>
</div>