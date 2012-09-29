<?php
/**
 * @package Module Random Article for Joomla! 2.5+
 * @version $Id: helper.php 16 2012-09-28 22:00:55Z king.arthur.pt@gmail.com $
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
			return -2;
		
		$categories = implode(",", $params->get('category'));
		
 
		//  The selected articles are published and have valid publish and unpublish dates
		if($params->get('subcategories'))
			$query = "SELECT * ".
						"FROM #__content ".
						"WHERE catid in ".
							"( SELECT id ".
								"FROM #__categories ".
								"WHERE parent_id in (".$categories.") OR id in (" .$categories .") ) ".
							"AND state = '1' ".
							"AND (publish_up <= '".date('Y-m-d H:i:s')."' OR publish_up = '0000-00-00 00:00:00') ".
							"AND (publish_down >= '".date('Y-m-d H:i:s')."' OR publish_down = '0000-00-00 00:00:00') ".
						"ORDER BY RAND() ".
						"LIMIT ".$numberArticles;
		else		
			$query = "SELECT * ".
						"FROM #__content ".
						"WHERE catid in (". $categories . ") ".
							"AND state = '1' ".
							"AND (publish_up <= '".date('Y-m-d H:i:s')."' OR publish_up = '0000-00-00 00:00:00') ".
							"AND (publish_down >= '".date('Y-m-d H:i:s')."' OR publish_down = '0000-00-00 00:00:00') ".
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
	
	/**
	 * Counts the number of characters in a String with HTML tags, ignoring the HTML tags.
	 *	 
	 * Thanks to Søren Løvborg for sharing this solution here: http://stackoverflow.com/a/1193598/1687176
	 *
	 * @param $maxLength The lenght of the substring
	 * @param $html The String to be cutted
	 * @return String $str A substring of $html that still is a valid HTML String 
	 */
	function substr_HTML($maxLength, $html, $isUtf8=true) {
		$printedLength = 0;
		$position = 0;
		$tags = array();
		$resultStr = "";
		
		// For UTF-8, we need to count multibyte sequences as one character.
		$re = $isUtf8
			? '{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;|[\x80-\xFF][\x80-\xBF]*}'
			: '{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;}';
		
		while ($printedLength < $maxLength && preg_match($re, $html, $match, PREG_OFFSET_CAPTURE, $position)) {
			list($tag, $tagPosition) = $match[0];
			
			// Print text leading up to the tag.
			$str = substr($html, $position, $tagPosition - $position);
			if ($printedLength + strlen($str) > $maxLength) {
				$resultStr = $resultStr . substr($str, 0, $maxLength - $printedLength);
				$printedLength = $maxLength;
				break;
			}
			
			$resultStr = $resultStr . $str;
			$printedLength += strlen($str);
			if ($printedLength >= $maxLength) break;
			
			if ($tag[0] == '&' || ord($tag) >= 0x80) {
				// Pass the entity or UTF-8 multibyte sequence through unchanged.
				$resultStr = $resultStr . $tag;
				$printedLength++;
			}
			else {
				// Handle the tag.
				$tagName = $match[1][0];
				if ($tag[1] == '/') {
					// This is a closing tag.
					
					$openingTag = array_pop($tags);
					assert($openingTag == $tagName); // check that tags are properly nested.
					
					$resultStr = $resultStr . $tag;
				}
	            else if ($tag[strlen($tag) - 2] == '/') {
					// Self-closing tag.
					$resultStr = $resultStr . $tag;
				}
				else {
					// Opening tag.
					$resultStr = $resultStr . $tag;
					$tags[] = $tagName;
				}
			}
	
			// Continue after the tag.
			$position = $tagPosition + strlen($tag);
		}
	
		// Print any remaining text.
		if ($printedLength < $maxLength && $position < strlen($html))
			$resultStr = $resultStr . substr($html, $position, $maxLength - $printedLength);
	
		// Close any open tags.
		//while (!empty($tags))
			//$resultStr = $resultStr . printf('</%s>', array_pop($tags)); 
		
		return $resultStr;
	}
}
?>
