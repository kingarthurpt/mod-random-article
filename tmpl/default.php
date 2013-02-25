<?php 
/**
 * @package Module Random Article for Joomla! 2.5+
 * @version $Id$
 * @author Artur Alves
 * @copyright (C) 2012 - Artur Alves
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

$html5 = $params->get('html5') ? 1 : 0;
$autoModuleId = $params->get('autoModuleId') ? true : false;
$moduleID = $params->get('moduleId');

if(isset($moduleID)) {
	$moduleID = explode(' ',trim($params->get('moduleId')));
	$moduleID = $moduleID[0];
}
else {
	if($autoModuleId)
		$moduleID = "modRandomArticle" . $module->id;
} 
 
$numberColumns = $params->get('numberColumns');
if($numberColumns > 0)
	$columnWidth = intval(100 / $numberColumns);
else {
	$numberColumns = 1;
	$columnWidth = 100;
}
?>

<?php if($html5): ?>
	<section <?php if(isset($moduleID)) echo 'id='.$moduleID.''; ?> class="random-article-wrapper <?php echo $params->get('moduleclass_sfx'); ?>">
<?php else: ?>
	<div <?php if(isset($moduleID)) echo 'id='.$moduleID.''; ?> class="random-article-wrapper <?php echo $params->get('moduleclass_sfx'); ?>">
<?php endif; ?>

		<?php
		// Shows an error if the user didn't select any category
		if($articles <= 0) {
			if($articles == -2)
				echo JText::sprintf('MOD_RANDOM_ARTICLE_ERROR_1');
			if($articles == -3)
				echo JText::sprintf('MOD_RANDOM_ARTICLE_ERROR_2');
		}
		else {
						
			// Shows a warning if the user didn't select the proper settings
			if($params->get('warnings')) {
				// Nothing is displayed
				if(!$params->get('title') && !$params->get('introtext') && !$params->get('introtextimage') && !$params->get('readmore') && !$params->get('fulltext') && !$params->get('fullarticleimage'))
					echo JText::sprintf('MOD_RANDOM_ARTICLE_WARNING_1');
			}
	
			if($numberColumns >= 1)
	 			if(($numberArticles + $numberK2Articles) % $numberColumns != 0)  
	 				$lineArticles = intval(($numberArticles + $numberK2Articles) / $numberColumns) + 1;
				else 
	 				$lineArticles = intval(($numberArticles + $numberK2Articles) / $numberColumns);
	 		
	 		$column = 0;
			$i = 0;
			$displayingHidden = 0;
			foreach($articles as $article) { ?>
							
					<?php if(($i % $lineArticles == 0) && ($column <= $numberColumns) && ($numberColumns > 1) && ($displayingHidden == 0) && ($numberArticles + $numberK2Articles) > 1) : ?>
						<div class="column col-<?php echo $column + 1; ?>" style="width:<?php echo $columnWidth; ?>%;">
					<?php $column++; ?>
					
					
					<?php else: ?>
						<?php if($displayingHidden == 2) : ?>
							</div>
							<div class="hidden-random-articles">
						<?php $displayingHidden = 1; endif; ?>
						
						<?php if($displayingHidden == 3) : ?>
							</div>
						<?php endif; ?>
					<?php endif; ?>
					
					<?php include 'article.php'; ?>
					
					<?php if($column >= 1 && $column <= $numberColumns && $i == $lineArticles - 1 ) : ?>
						</div>
					<?php endif; ?>
					<?php 											
				$i++;				
			}  // foreach
			
		} ?>
		
<?php if($html5) : ?>
	</section>
<?php else: ?>
	</div>
<?php endif; ?>

<?php if($params->get('customCss')): ?>
	<style type="text/css">
		.random-article .title h<?php echo $params->get('styleTitle'); ?> <?php if($params->get('linktitle')) echo "a"; ?> {
			color: <?php echo $params->get('styleTitleColor'); ?>;
		}
		
		<?php if($params->get('linktitle')): ?>
		.random-article .title h<?php echo $params->get('styleTitle'); ?> a:hover {
			color: <?php echo $params->get('styleTitleColorOver'); ?>;
		}
		<?php endif; ?>
		
		<?php if($params->get('readmore')): ?>
		.random-article .readmore a {
			color: <?php echo $params->get('styleReadmoreColor'); ?>;
		}
		
		.random-article .readmore a:hover {
			color: <?php echo $params->get('styleReadmoreColorOver'); ?>;
		}
		<?php endif; ?>		
	</style>
<?php endif; ?>

