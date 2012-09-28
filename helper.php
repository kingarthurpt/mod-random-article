<?php
/**
 * @package Module Random Article for Joomla! 2.5+
 * @version $Id$
 * @author Artur Alves
 * @copyright (C) 2010- Artur Alves
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
class modRandomArticleHelper {

	/**
	 * Picks $numberArticles random articles from the chosen categories.
	 * @return Array $rows On success returns an array with the random articles, otherwise returns -1
	 */	
	function getArticles( &$params ) {
		
		// Converts numberArticles to a number.
		$numberArticles = intval($params->get('numberArticles'));
		 
		// Checks if there is any selected category.
		if(count($params->get('category')) <= 0)
			return -1;
		
		$categories = implode(",", $params->get('category'));
		
		// Database query
		if($params->get('subcategories'))
			$query = "SELECT * ".
						"FROM #__content ".
						"WHERE catid in ".
							"( SELECT id ".
								"FROM #__categories ".
								"WHERE parent_id in (".$categories.") OR id in (" .$categories .") ) ".
								"ORDER BY RAND() ".
								"LIMIT ".$numberArticles;
		else		
			$query = "SELECT * ".
						"FROM #__content ".
						"WHERE catid in (". $categories . ") ".
						"ORDER BY RAND() ".
						"LIMIT ".$numberArticles;

		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		return $rows;
	}
   
	/**
	 * Gets the correct URL for a given $article.
	 * @return String $url The URL to the $article 
	 */   
	function getUrl( &$article ) {
		$id = $article->id;
		$link = "index.php?option=com_content&view=article&id=".$id;

		// Checks if there is a menu item linked to $article and applies its ItemID to the URL. 
		$query = " SELECT * FROM #__menu WHERE link = '". $link ."'";
   	
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$rows = $db->loadObjectList();
   	
		if(isset($rows[0]))
			$url = $link . "&Itemid=" .$rows[0]->id;
		else
			$url = $link;
   	
		return $url;
	}
}
?>
