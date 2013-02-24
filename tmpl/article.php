<?php if($html5) : ?>
	<article class="random-article <?php if($article->type == "K2") echo "random-k2-article "; ?> ">
<?php else: ?>
	<div class="random-article <?php if($article->type == "K2") echo "random-k2-article "; ?> ">
<?php endif; ?>

	<?php
		// Shows a warning if the user didn't select the proper settings
		if($params->get('warnings')) {
			// Displaying Fulltext in an article without the readmore separator			
			if(!$params->get('introtext') && $params->get('fulltext'))
				if($article->introtext != null && $article->fulltext == null)
					echo JText::sprintf('MOD_RANDOM_ARTICLE_WARNING_2');
		}
	?>			

	<?php if($params->get('title')): ?>
		<?php
			$titleTag = "<h" . $params->get('styleTitle') . ">";
			$titleCloseTag = "</h" . $params->get('styleTitle') . ">";
		?>
		<div class="title">
			<?php if($params->get('linktitle')): ?>
				<?php echo $titleTag; ?>
					<a href="<?php echo $urls[$i]; ?>"><?php echo $article->title; ?></a>
				<?php echo $titleCloseTag; ?>
			<?php else: ?>
				<?php echo $titleTag; ?>
					<?php echo $article->title; ?>
				<?php echo $titleCloseTag; ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	
	<?php if($params->get('introtext')) : ?>
		<div class="introtext">
			
		<?php if($params->get('introtextlimit') == 0) : 
				 echo $article->introtext; 
			else: 
				$limitCount = intval($params->get('introtextlimitcount'));
				if($limitCount < 0)
					$limitCount = 0;
				
				// Limits the $introtext by word count
				if($params->get('introtextlimit') == 1) {
					
					// The limit exceeds the word count
					$totalWordCount = str_word_count(strip_tags($article->introtext), 0, "0123456789");
					if($totalWordCount <= $limitCount)
						echo $article->introtext;
					else {
														
						// Find the position of the $limitCount word, so it can be used in modRandomArticleHelper::substr_HTML()
						$newLimit = modRandomArticleHelper::strposnth(strip_tags($article->introtext), " ", $limitCount);
						$introtext = modRandomArticleHelper::substr_HTML($newLimit, $article->introtext);
						echo $introtext . "...";
					}
				}
				
				// Limits the $introtext by character count
				elseif($params->get('introtextlimit') == 2) {
					
					
					// The limit exceeds the character count
					$totalCharCount = strlen(strip_tags($article->introtext));
					
					if($totalCharCount <= $limitCount)
						echo $article->introtext;
					else {

						// Old function to be used if the new function doesn't work. 
						//$introtext = substr($article->introtext, 0, $limitCount);
						
						// New function to ignore HTML tags when limiting the introtext.						
						$introtext = modRandomArticleHelper::substr_HTML($limitCount, $article->introtext);
						
						echo $introtext . "...";
					}
				}										
			?>
		<?php endif; ?>
		</div>
	<?php endif; ?>
	
	<?php if($params->get('introtextimage') && $article->type != "K2") : 

		// Copied this code from componentes/com_content/views/category/tmpl/blog_item.php
		$images = json_decode($article->images);
		if (isset($images->image_intro) and !empty($images->image_intro)) : ?>
			<div class="introimage">
				<?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
				<div class="img-intro-<?php echo htmlspecialchars($imgfloat); ?>">
					<a href="<?php echo $urls[$i]; ?>">
						<img
						<?php if ($images->image_intro_caption):
									echo 'class="caption"'.' title="' .htmlspecialchars($images->image_intro_caption) .'"';
							endif; ?>
						src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>"/>
					</a>
				</div>
			</div>			
		<?php endif; ?>
	<?php endif; ?>
	
	<?php 
	if($params->get('introtextimage') && $article->type == "K2") :
		$imgsrc = modRandomArticleHelper::getK2ArticleImage($article, $params->get('k2imagesize')); 	?>				
		<div class="introimage">
			<a href="<?php echo $urls[$i]; ?>">
				<img src="<?php echo $imgsrc; ?>" alt="<?php echo $article->title; ?>"/>
			</a>
		</div>
	
	<?php endif; ?>
	
	<?php if($params->get('readmore')) : ?>
		<div class="readmore"><a href="<?php echo $urls[$i]; ?>"> <?php echo JText::sprintf('MOD_RANDOM_ARTICLE_READMORE'); ?> </a></div>
	<?php endif; ?>
		
	<?php if($params->get('fulltext')) : ?>
		<div class="fulltext"> 
			<?php if($params->get('fullarticleimage')  && $article->type != "K2") : 
				
					// Copied this code from componentes/com_content/views/article/tmpl/default.php
					$images = json_decode($article->images);  
					if (isset($images->image_fulltext) and !empty($images->image_fulltext)) : ?>
					<div class="fullarticleimage">
						<?php $imgfloat = (empty($images->float_fulltext)) ? $params->get('float_fulltext') : $images->float_fulltext; ?>
						<div class="img-fulltext-<?php echo htmlspecialchars($imgfloat); ?>">
						<img
						        <?php if ($images->image_fulltext_caption):
						                echo 'class="caption"'.' title="' .htmlspecialchars($images->image_fulltext_caption) .'"';
						        	endif; ?>
						        src="<?php echo htmlspecialchars($images->image_fulltext); ?>" alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>"/>
						</div>
					</div>
					<?php endif; ?>	
			<?php endif; ?>
			<?php echo $article->fulltext; ?> 
		</div>
	<?php endif; ?>
<?php if($html5) : ?>
	</article>
<?php else: ?>
	</div>
<?php endif; ?>
	