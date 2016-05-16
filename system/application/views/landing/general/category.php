<?=headerView($category->name, $category->name . " services and service providers");?>
<?php $children = $category->getChildren(); ?>


<div id="divLandingPage">

	<?php if(!empty($sellers)) { $this->load->view('landing/general/sellers', array("sellers" => $sellers)); } ?>
	<?php if(!empty($serviceRequests)) { $this->load->view('landing/general/serviceRequests', array("serviceRequests" => $serviceRequests)); } ?>
	<?php if(!empty($children)) { $this->load->view('landing/general/childrenCategories', array("categories" => $children)); } ?>
	
</div>

<?=$this->load->view('footer')?>