<?php 

if($showCategories)
	$categories = hashArrayByObjectProperty($this->categorylib->getCategoriesByUser($user), "name");

?>

Name : <?=isset($user->info->companyName) ? $user->info->companyName : "" ?><br/>
Email : <?=$user->email?><br/>
Description : <?=isset($user->info->description) ? $user->info->description : "" ?><br/>
<?php if($showCategories) { ?>Categories : <?=implode(", ", array_keys($categories))?><br/> <?php } ?>
Website : <?=isset($user->info->website) ? "<a href='{$user->info->website}'>{$user->info->website}</a>" : "" ?><br/>
Primary Phone : <?=isset($user->info->phone) ? $user->info->phone : "" ?><br/>