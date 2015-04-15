<?php 
/**
 * @package Module Random Article for Joomla! 2.5+
 * @version $Id: default.php 78 2013-08-31 21:09:42Z artur.ze.alves@gmail.com $
 * @author Artur Alves
 * @copyright (C) 2012 - Artur Alves
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

// $html5 = $params->get('html5') ? 1 : 0;
$autoModuleId = $params->get('autoModuleId') ? true : false;
$moduleID = $params->get('moduleId');

if (isset($moduleID)) {
    $moduleID = explode(' ',trim($params->get('moduleId')));
    $moduleID = $moduleID[0];
} else {
    if ($autoModuleId) {
        $moduleID = "modRandomArticle" . $module->id;
    }
} 
 
$numberColumns = $params->get('numberColumns');
$columnMargin = $params->get('columnMargin');

if ($numberColumns > 0) {
    if ($params->get('columnWidth') > 0 && $params->get('columnWidth') <= 100) {
        $columnWidth = $params->get('columnWidth');
    } else {
        $columnWidth = intval(100 / $numberColumns);
    }
} else {
    $numberColumns = 1;
    $columnWidth = 100;
}
?>

<?php if ($params->get('html5')): ?>
    <section <?php if (isset($moduleID)) echo 'id="'.$moduleID.'"'; ?> class="random-article-wrapper <?php echo $params->get('moduleclass_sfx'); ?>">
<?php else: ?>
    <div <?php if (isset($moduleID)) echo 'id="'.$moduleID.'"'; ?> class="random-article-wrapper <?php echo $params->get('moduleclass_sfx'); ?>">
<?php endif; ?>

        <?php
        // Shows an error if the user didn't select any category
        if ($articles <= 0) {
            // Shows a warning if there are no articles to be displayed
            if ($articles == 0 && $params->get('warnings')) {
                echo JText::sprintf('MOD_RANDOM_ARTICLE_WARNING_3');
            }
        } else {
                        
            // Shows a warning if the user didn't select the proper settings
            if ($params->get('warnings')) {
                // Nothing is displayed
                if (!$params->get('title') 
                    && !$params->get('introtext') 
                    && !$params->get('introtextimage') 
                    && !$params->get('readmore') 
                    && !$params->get('fulltext') 
                    && !$params->get('fullarticleimage')) {
                    echo JText::sprintf('MOD_RANDOM_ARTICLE_WARNING_1');
                }
            }
    
            if ($numberColumns >= 1) {
                $columnArticles = intval(($numberArticles + $numberK2Articles) / $numberColumns);
            }
            
            $columns = array();
            for($columnIndex = 0; $columnIndex < $numberColumns; $columnIndex++) {
                $columns[$columnIndex] = '<div class="column col-' . ($columnIndex + 1) . '">';
            }

            $articleIndex = 0;
            while($articleIndex < count($articles)) {
                foreach ($columns as $columnIndex => $column) {
                    if (isset($articles[$articleIndex])) {
                        $columns[$columnIndex] .= modRandomArticleHelper::getArticleHtml($params, $articles, $articleIndex);
                        $articleIndex++;
                    }
                }
            }

            for($columnIndex = 0; $columnIndex < $numberColumns; $columnIndex++) {
                echo $columns[$columnIndex] . '</div>';
            }
            
            // $output = "";
            // $numberArticlesShown = 0;
            // for($columnIndex = 0; $columnIndex < $numberColumns; $columnIndex++) {
            //     $output .= '<div class="column col-' . ($columnIndex + 1) . '">';

            //     $articleOutput = "";
            //     for($articleIndex = 0; $articleIndex < $columnArticles; $articleIndex++) {
            //         if (isset($articles[$numberArticlesShown])) {
            //             $output .= modRandomArticleHelper::getArticleHtml($params, $articles[$numberArticlesShown]);
            //             $numberArticlesShown++;
            //         } else {
            //             $articleIndex = $columnArticles;
            //             $columnIndex = $numberColumns;
            //         }
            //     }

            //     $output .= '</div>';
            //     echo $output;
            // }

        } ?>
        
        <div class="clearfix"></div>
<?php if ($params->get('html5')) : ?>
    </section>
<?php else: ?>
    </div>
<?php endif; ?>

<?php
    include JPATH_ROOT.DS.'modules'.DS.'mod_random-article'.DS.'css'.DS.'style.php';
    $document = JFactory::getDocument();
    $document->addStyleSheet(JURI::base().'modules'.DS.'mod_random-article'.DS.'css'.DS.'style.css');
?>