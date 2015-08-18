<?php
/**
 * @package Module Random Article for Joomla! 2.5+
 * @version $Id: helper.php 75 2013-08-29 05:21:29Z artur.ze.alves@gmail.com $
 * @author Artur Alves
 * @copyright (C) 2010- Artur Alves
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

class modRandomArticle
{
    private $urls;

    public function getUrls()
    {
        return $this->urls;
    }

    public function setUrls($urls)
    {
        $this->urls = $urls;
    }

    /**
     * Picks $numberArticles random articles from the chosen categories.
     * @return Array $rows On success returns an array with the random articles, otherwise returns -1
     */    
    public function getJoomlaArticles( &$params )
    {        
        // Converts numberArticles to a number.
        $numberArticles = intval($params->get('numberArticles'));
        if($numberArticles <= 0) {
            throw new Exception(JText::sprintf('MOD_RANDOM_ARTICLE_ERROR_2'));
        }
         
        // Checks if there is any selected category.
        if($numberArticles > 0 && count($params->get('category')) <= 0)
            throw new Exception(JText::sprintf('MOD_RANDOM_ARTICLE_ERROR_1'));
        
        if($params->get('category')) {
            $categories = implode(",", $params->get('category'));
        }
            
        if($params->get('excludeAccessLevel')) {
            $excludeAccessLevel = implode(",", $params->get('excludeAccessLevel'));
            
            if($excludeAccessLevel) {
                $queryExclude = "AND a.access not in (". $excludeAccessLevel . ") ";
            }
        } else {
            $queryExclude = "";
            $excludeAccessLevel = "";
        }
                
        // Sets the timezone to match the Joomla configuration file
        $app = JFactory::getApplication();
        date_default_timezone_set ($app->getCfg('offset'));
 
        if(count($params->get('category')) > 0) {
            //  The selected articles are published and have valid publish and unpublish dates
            $query = "SELECT a.*, c.title as 'cat_title', 'Joomla' as type " .
                        "FROM #__content a " .
                        "LEFT JOIN #__categories c ON c.id = a.catid " .
                        "WHERE a.catid in ";
                            
            // Selects articles from the subcategories. 
            if($params->get('subcategories'))
                $query .= "( SELECT id FROM #__categories WHERE (parent_id in (".$categories.") OR id in (" .$categories .")) ".$queryExclude." )";
            else
                $query .= "( ".$categories." ) ";
                            
            $query .= " AND a.state = '1' ";
            
            $language = JFactory::getLanguage();
			
            if($language->getTag()){
			    $query .= "AND a.language IN('*','".$language->getTag()."' ";
			}

                        // Disables time restrictions and selects articles without checking if the dates are correct.
                        if(!$params->get('timerestrictions'))
                            $query .= "AND (a.publish_up <= '".date('Y-m-d H:i:s')."' OR a.publish_up = '0000-00-00 00:00:00') ".
                                        "AND (a.publish_down >= '".date('Y-m-d H:i:s')."' OR a.publish_down = '0000-00-00 00:00:00') ";
                                        
            $query .= $queryExclude;
                                        
            $query .= "ORDER BY ".$params->get('ordering'). " " .$params->get('orderDirection'). " " . "LIMIT " . $numberArticles;
                        
            $db = JFactory::getDBO();
            $db->setQuery($query);
            $rows = $db->loadObjectList();
        }
        
        if(count($rows) > 0) {
            return $rows;
        } else {
            return 0;
        }
    }

    /**
     * Picks $numberArticles random articles from the chosen categories.
     * @return Array $rows On success returns an array with the random articles, otherwise returns -1
     */    
    public function getK2Articles( &$params )
    {        
        // Converts numberArticles to a number.
        $numberArticlesK2 = intval($params->get('numberArticlesK2'));
        if($numberArticlesK2 < 0) {
            throw new Exception(JText::sprintf('MOD_RANDOM_ARTICLE_ERROR_2'));
        }
         
        // Checks if there is any selected category.
        if($numberArticlesK2 > 0 && count($params->get('categoryk2')) <= 0) {
            throw new Exception(JText::sprintf('MOD_RANDOM_ARTICLE_ERROR_1'));
        }
        
        if($params->get('categoryk2')) {
            $k2categories = implode(",", $params->get('categoryk2'));
        }
            
        if($params->get('excludeAccessLevel')) {
            $excludeAccessLevel = implode(",", $params->get('excludeAccessLevel'));
            
            if($excludeAccessLevel) {
                $queryExcludeCat = "AND c.access not in (". $excludeAccessLevel . ") ";
                $queryExcludeItm = "AND i.access not in (". $excludeAccessLevel . ") ";
            }
        } else {
            $queryExclude = "";
            $excludeAccessLevel = "";
            $queryExcludeCat = "";
            $queryExcludeItm = "";
        }
                
        // Sets the timezone to match the Joomla configuration file
        $app = JFactory::getApplication();
        date_default_timezone_set ($app->getCfg('offset'));
        
        if($params->get('categoryk2') && count($params->get('categoryk2')) > 0) {
            //  The selected articles are published and have valid publish and unpublish dates
            $query = "SELECT i.*, 'K2' as type, c.alias as categoryalias, c.name as cat_title ".
                        "FROM #__k2_items as i ".
                        "LEFT JOIN #__k2_categories c ON c.id = i.catid ".
                        "WHERE catid in ";
                                            
                        // Selects articles from the subcategories. 
                        if($params->get('subcategoriesk2'))
                            $query .= "( SELECT id FROM #__k2_categories WHERE (parent in (".$k2categories.") OR id in (" .$k2categories .")) ".$queryExcludeCat." ) ";
                        else
                            $query .= "( ".$k2categories." ) ";
                            
            $query .= "AND i.published = '1' ";
            
                        // Disables time restrictions and selects articles without checking if the dates are correct.
                        if(!$params->get('timerestrictions'))
                            $query .= "AND (i.publish_up <= '".date('Y-m-d H:i:s')."' OR i.publish_up = '0000-00-00 00:00:00') ".
                                        "AND (i.publish_down >= '".date('Y-m-d H:i:s')."' OR i.publish_down = '0000-00-00 00:00:00') ";
                                        
            $query .= $queryExcludeItm;
                                        
            if($params->get('ordering') == "rand()")
                $ordering = $params->get('ordering');
            else
                $ordering = "i." . $params->get('ordering');
                
            $query .=     "ORDER BY ".$ordering." " .$params->get('orderDirection')." ".                        
                        "LIMIT " . $numberArticlesK2;

            $db = JFactory::getDBO();
            $db->setQuery($query);
            $k2rows = $db->loadObjectList();
            
        }
        
        if(count($k2rows) > 0) {
            return $k2rows;
        } else {
            return 0;
        }
    }

}
?>
