<?=$this->load->view('header', array("title" => $type == Service_Request::SUBMIT_TYPE_CREATE ? "Create a Service Request" : "Modify a Service Request"))?>

<?php 
	$formUrl = $type == Service_Request::SUBMIT_TYPE_CREATE && $page == 1 ? "/service_request/create/2" : "/service_request/submit";
	if($type == Service_Request::SUBMIT_TYPE_MODIFY)
		$formUrl .= "/" . $serviceRequest->id;
?>

<div id="divSRSubmit">
	<div class="Header"><?=$type == Service_Request::SUBMIT_TYPE_MODIFY ? "Modify Service Request" : "Create a Service Request"?></div>
	<form id="frmServiceRequest" name="frmServiceRequest" action="<?=$formUrl?>" method="POST"/>
		<?php 
			if($type == Service_Request::SUBMIT_TYPE_CREATE && $page == 1) 
				$this->load->view("service_request/submit1", array("category" => isset($selectedCategory) ? $selectedCategory : null));
			else if($type == Service_Request::SUBMIT_TYPE_CREATE && $page == 2)
				$this->load->view("service_request/submit2");
			else if($type == Service_Request::SUBMIT_TYPE_MODIFY)
			{
				$this->load->view("service_request/submit1", array("serviceRequest" => $serviceRequest));
				?><br/><?php
				$this->load->view("service_request/submit2", array("serviceRequest" => $serviceRequest));
			}
		 ?>
	</form>
</div>

<?=$this->load->view('footer')?>