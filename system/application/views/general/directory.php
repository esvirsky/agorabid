<?=$this->load->view('header')?>

<?php

	global $directoryViewCategoryLandingPages;
	$directoryViewCategoryLandingPages = hashArrayByObjectProperty($this->landingpagelib->getCategoryLandingPages(), "categoryId");
	$nonCategoryLandingPages = $this->landingpagelib->getNonCategoryLandingPages();
	
	/**
	 * Recursively prints out a category node
	 * 
	 * @param $node
	 * @return unknown_type
	 */
	function _printCategoryNode($node, $parent = null)
	{
		global $directoryViewCategoryLandingPages;
		$landingPage = $directoryViewCategoryLandingPages[$node->id];

		echo "<li>\n";
		echo "	<a href='" . $landingPage->getUri() . "'>$node->displayName</a>\n";
	
		if(!empty($node->children))
			echo "	<ul class='ChildrenCategoryNodes'>\n";
		
		foreach($node->children as $displayName => $child)
			_printCategoryNode($child, $node);
		
		if(!empty($node->children))
			echo "	</ul>\n";
	
		echo "</li>\n";	
	}
	
?>

<div id="divGeneralDirectory">
	
	
	<div class="Header">Categories</div>
	<div id="divCategories" class="SectionBox">
		<ul>
			<?php foreach($this->categorylib->getCategoryTree() as $category) { _printCategoryNode($category); } ?>
		</ul>
	</div>
	
	<?php if(!empty($nonCategoryLandingPages)) { ?>
		<div class="Header">More Landing Pages</div>
		<div id="divLandingPages" class="SectionBox Section">
			<ul>
				<?php foreach($nonCategoryLandingPages as $landingPage) { ?>
					<li><a href="<?=$landingPage->getUri()?>"><?=$landingPage->name?></a></li>
				<?php } ?>
			</ul>
		</div>
	<?php } ?>
	
	<div class="Header">Blogs</div>
	<div id="divBlogs" class="SectionBox Section">
		<ul>
			<li><a href="/blog/category/computer-repair-and-installation">Computer repair and installation</a></li>
		</ul>
	</div>
	
	<div class="Header">New Articles</div>
	<div class="SectionBox">
		<ul>
			<li><a href="/article/1/inspecting-air-ducts">How to Inspect Your Air Ducts</a></li>
			<li><a href="/blog/5/computer-repair-and-installation/computer-devices-and-accessories">Computer, devices, and accessories</a></li>
			<li><a href="/blog/21/computer-repair-and-installation/how-a-computer-works-computer-hardware">How a Computer Works – Computer Hardware</a></li>
		</ul>
	</div>
</div>

<?=$this->load->view('footer')?>