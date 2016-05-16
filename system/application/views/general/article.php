<?=headerView($article->title, $article->description);?>

<div id="divGeneralArticle">

	<div class="Header"><?=$article->title?></div>
	<div class="SectionBox"><?=$article->body?></div>
	
</div>

<?=$this->load->view('footer')?>