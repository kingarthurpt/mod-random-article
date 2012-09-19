<?php
/**
 * @package Module Random Article for Joomla! 2.5+
 * @version $Id$
 * @author Artur Alves
 * @copyright (C) 2010- Artur Alves
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
class modRandomArticleHelper {
	
	function getArticles( &$params ) {
		$numberArticles = intval($params->get('numberArticles'));
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
   
	function getUrl( &$article ) {
		$id = $article->id;
   	
		$link = "index.php?option=com_content&view=article&id=".$id; 
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
