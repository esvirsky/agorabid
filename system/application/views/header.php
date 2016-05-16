<?php 

	$this->load->helper('url');
	switch(uri_string())
	{
		case "":
			$selected = "imgMenuHome";
			break;
		case "/service_request/create":
			$selected = "imgMenuNew";
			break;
		case "/service_request/search":
			$selected = "imgMenuSearch";
			break;
		default:
			$selected = null;
	}
	
	$message = isset($message) ? $message : $this->session->userdata("message");
	$this->session->unset_userdata("message");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?=$this->config->item("site_name")?> - <?=$title?></title>
		
		<?php $this->carabiner->display("global"); ?>
		
		<meta name="description" content="<?=isset($metaDescription) ? $metaDescription : "AgoraBid is an online service marketplace where people can buy and sell any kind of services using an auction style bidding platform."?>" />
		<meta name="google-site-verification" content="TjWwC9gzukk2A76qvY7I8Lv9nHlLBEabTBsmreDoaUM" />	
		<meta http-equiv="Pragma" content="no-cache">	
	</head>

	<body>
		
		<div id="divIE6" class="SectionBox">
			We apologize, currently our site doesn't fully support Internet Explorer 6. 
			We are in the process of adding such support. In the meanwhile please upgrade your IE browser
			to the <a href="http://www.microsoft.com/windows/Internet-explorer/default.aspx">latest version</a>, or use one of these excellent browsers:
			
			<a href="http://www.mozilla.com/en-US/firefox/personal.html">Firefox</a>,
			<a href="http://www.google.com/chrome">Chrome</a>, or 
			<a href="http://www.apple.com/safari/download/">Safari</a>.
			
		</div>
		
		<div id="divHeader">
			<div id="divLogo"><a href="/"><img id="imgLogo" src="/images/logo.png" alt="Logo" /></a></div>
			<div id="divAccount">
				<?php if($this->authlib->isLoggedIn()) { ?>
					 <a href="/user/manager" >My Account <span class="UsernameSpan">(<?=$this->userlib->getLoggedInUser()->username?>)</span></a> | <a href="/auth/logout">Logout</a>
				<?php } else { ?>
					<a href="/auth/login" >Login</a> | <a href="/auth/register">Register</a>
				<?php } ?>
			</div>
			
			<div id="divMenu">
				<a href="/"><img id="imgMenuHome" src="<?=$selected == "imgMenuHome" ? "/images/menu_home_selected.png" : "/images/menu_home.png"?>" alt="Menu Home" /></a
				><a href="/service_request/create"><img id="imgMenuNew" src="<?=$selected == "imgMenuNew" ? "/images/menu_new_selected.png" : "/images/menu_new.png"?>" alt="Menu New Request" /></a
				><a href="/service_request/search"><img id="imgMenuSearch" src="<?=$selected == "imgMenuSearch" ? "/images/menu_search_selected.png" : "/images/menu_search.png"?>" alt="Menu Search Requests" /></a>
				<input id="hdnHeaderMenuSelected" type="hidden" value="<?=$selected?>"/>
			</div>
		</div>
	
	<?php if(!$this->config->item("live")) {?>
		<div id="divValidationErrors"><?= function_exists("validation_errors") ? validation_errors() : "" ; ?></div>
	<?php } ?>
	
	<div id="divTopErrorMessage"><?= isset($error) ? $error : "" ?></div>
	
	<div id="divContent" class="ClearAfter">
		<div id="divTopMessage" class="SectionBox" <?=empty($message) ? "style='display: none;'" : "" ?>><?= !empty($message) ? $message : ""?></div>