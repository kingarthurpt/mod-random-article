<?php 
/**
 * @package Module Random Article for Joomla! 2.5+
 * @version $Id$
 * @author Artur Alves
 * @copyright (C) 2010- Artur Alves
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<div class="random-article-wrapper <?php echo $params->get('moduleclass_sfx'); ?>">

	<?php 
	$i = 0;	
	foreach($articles as $article) { ?>
	
		<div class="random-article">
			<?php if($params->get('title')) : ?>
				<div class="title">
					<?php if($params->get('linktitle')) : ?>
						<h2><a href="<?php echo $urls[$i]; ?>"><?php echo $article->title; ?></a></h2>
					<?php else: ?>
						<h2><?php echo $article->title; ?></h2>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<?php if($params->get('introtext')) : ?>
				<div class="introtext"> <?php echo $article->introtext; ?> </div>
			<?php endif; ?>
			
			<?php if($params->get('readmore')) : ?>
				<div class="readmore"><a href="<?php echo $urls[$i]; ?>"> <?php echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE'); ?> </a></div>
			<?php endif; ?>
				
			<?php if($params->get('fulltext')) : ?>
				<div class="fulltext"> <?php echo $article->fulltext; ?> </div>
			<?php endif; ?>
		</div>
		<?php 
		$i++;	
	} 
	?>	
</div>
