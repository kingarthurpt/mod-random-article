<?php

class modRandomArticleHelper {
	
	function getArticles( &$params ) {
		$numberArticles = $params->get('numberArticles');		
		
		// Database query	
		if($params->get('subcategories'))
			$query = "SELECT * ".
						"FROM #__content ".
						"WHERE catid in ".
							"( SELECT id ".
								"FROM #__categories ".
								"WHERE parent_id= ".$params->get('category')." OR id= " .$params->get('category') ." ) ".
								"ORDER BY RAND() ".
								"LIMIT ".$numberArticles;
		else		
			$query = "SELECT * ".
						"FROM #__content ".
						"WHERE catid = ". $params->get('category') . " ".
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
