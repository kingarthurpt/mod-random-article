<?php
/**
 * @package Module Random Article for Joomla! 2.5+
 * @version $Id$
 * @author Artur Alves
 * @copyright (C) 2010- Artur Alves
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

class modRandomArticleHelper {

	/**
	 * Picks $numberArticles random articles from the chosen categories.
	 * @return Array $rows On success returns an array with the random articles, otherwise returns -1
	 */	
	public static function getArticles( &$params ) {
		
		// Converts numberArticles to a number.
		$numberArticles = intval($params->get('numberArticles'));
		$numberArticlesK2 = intval($params->get('numberArticlesK2'));
		if($numberArticles < 0 || $numberArticlesK2 < 0 || ($numberArticlesK2 <= 0 && $numberArticles <= 0))
			return -3;
		 
		// Checks if there is any selected category.
		if(count($params->get('category')) <= 0 && count($params->get('categoryk2')) <= 0)
			return -2;
		
		if($params->get('category'))
			$categories = implode(",", $params->get('category'));
		
		if($params->get('categoryk2'))
			$k2categories = implode(",", $params->get('categoryk2'));
		
		// Sets the timezone to match the Joomla configuration file
		$app = JFactory::getApplication();
		date_default_timezone_set ($app->getCfg('offset'));
 
  		if(count($params->get('category')) > 0) {
			//  The selected articles are published and have valid publish and unpublish dates
			$query = "SELECT *, 'Joomla' as type ".
						"FROM #__content ".
						"WHERE catid in ";
											
						// Selects articles from the subcategories. 
						if($params->get('subcategories'))
							$query .= "( SELECT id FROM #__categories WHERE parent_id in (".$categories.") OR id in (" .$categories .") )";
						else
							$query .= "( ".$categories." ) ";
							
			$query .= "AND state = '1' ";
			
						// Disables time restrictions and selects articles without checking if the dates are correct.
						if(!$params->get('timerestrictions'))
							$query .= "AND (publish_up <= '".date('Y-m-d H:i:s')."' OR publish_up = '0000-00-00 00:00:00') ".
										"AND (publish_down >= '".date('Y-m-d H:i:s')."' OR publish_down = '0000-00-00 00:00:00') ";
										
			$query .= 	"ORDER BY RAND() ".
						"LIMIT ".$numberArticles;
						
			$db = JFactory::getDBO();
			$db->setQuery($query);
			$rows = $db->loadObjectList();			
			
		}
		
		if($params->get('categoryk2') && count($params->get('categoryk2')) > 0) {
			//  The selected articles are published and have valid publish and unpublish dates
			$query = "SELECT i.*, 'K2' as type, c.alias as categoryalias ".
						"FROM #__k2_items as i ".
						"LEFT JOIN #__k2_categories c ON c.id = i.catid ".
						"WHERE catid in ";
											
						// Selects articles from the subcategories. 
						if($params->get('subcategoriesk2'))
							$query .= "( SELECT id FROM #__k2_categories WHERE parent in (".$k2categories.") OR id in (" .$k2categories .") ) ";
						else
							$query .= "( ".$k2categories." ) ";
							
			$query .= "AND i.published = '1' ";
			
						// Disables time restrictions and selects articles without checking if the dates are correct.
						if(!$params->get('timerestrictions'))
							$query .= "AND (i.publish_up <= '".date('Y-m-d H:i:s')."' OR i.publish_up = '0000-00-00 00:00:00') ".
										"AND (i.publish_down >= '".date('Y-m-d H:i:s')."' OR i.publish_down = '0000-00-00 00:00:00') ";
										
			$query .= 	"ORDER BY RAND() ".
						"LIMIT ".$numberArticlesK2;

			$db = JFactory::getDBO();
			$db->setQuery($query);
			$k2rows = $db->loadObjectList();
			
		}
		
		if(!is_array($rows))
			return $k2rows;
		if(!is_array($k2rows))
			return $rows;
			
		return array_merge($rows, $k2rows);
	}
   
	/**
	 * Gets the correct URL for a given $article.
	 * @return String $url The URL to the $article 
	 */   
	public static function getUrl( &$article ) {
		$id = $article->id;
		
		if($article->type == 'Joomla') {
			$link = "index.php?option=com_content&view=article&id=".$id;

			// Checks if there is a menu item linked to $article and applies its ItemID to the URL. 
			$query = "SELECT * FROM #__menu WHERE link = '". $link ."'";
	   	
			$db = JFactory::getDBO();
			$db->setQuery($query);
			$rows = $db->loadObjectList();
	   	
			if(isset($rows[0]))
				$url = $link . "&Itemid=" .$rows[0]->id;
			else
				$url = $link;
				
			return $url;
		}
		
		// Possible bug here. This $link may need the &Itemid to work properly
		elseif($article->type == 'K2') {
			// Copied from mod_k2_content - helper.php - readmode link
			require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');
			$link = urldecode(JRoute::_(K2HelperRoute::getItemRoute($article->id.':'.urlencode($article->alias), $article->catid.':'.urlencode($article->categoryalias))));
			
			return $link;
		}
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
	public static function substr_HTML($maxLength, $html, $isUtf8=true) {
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
		while (!empty($tags)) {
			//printf('</%s>', array_pop($tags));
			$resultStr = $resultStr . "</" . array_pop($tags) . ">";
		} 
		
		return $resultStr;
	}
	
	/**
	 * A handy function to get the position of nth occurance of a substring in a string
	 *
	 * Thanks for webKami for sharing this function
	 * http://www.webkami.com/programming/php/php-function-str-nth-pos/php-function-str-nth-pos-1-0-0.php
	 */
	public static function strposnth($haystack, $needle, $nth=1, $insenstive=0) {
		//if its case insenstive, convert strings into lower case
		if ($insenstive) {
			$haystack=strtolower($haystack);
			$needle=strtolower($needle);
		}
		
		//count number of occurances
		$count=substr_count($haystack,$needle);
  
		//first check if the needle exists in the haystack, return false if it does not
		//also check if asked nth is within the count, return false if it doesnt
		if ($count<1 || $nth > $count) return false;
  
		//run a loop to nth number of accurance
		//start $pos from -1, cause we are adding 1 into it while searchig
		//so the very first iteration will be 0
		for($i=0,$pos=0,$len=0;$i<$nth;$i++) {   
			
			//get the position of needle in haystack
			//provide starting point 0 for first time ($pos=0, $len=0)
			//provide starting point as position + length of needle for next time
			$pos=strpos($haystack,$needle,$pos+$len);

			//check the length of needle to specify in strpos
			//do this only first time
			if ($i==0) $len=strlen($needle);
		}
  
		//return the number
		return $pos;
	}
	
	/**
	 * Writes a detailed log file to be used in bug report
	 * @param $opt the type of output to be logged: 1=settings 2=article 3=url 4=html
	 * @param $data the output to be logged
	 */
	public static function logThis($opt = 1, $data = "") {
		$filename = "tmp".DS."mod_random-article-debuglogfile.txt";
		$timestamp = "Timestamp: ".date('Y-m-d H:i:s') . "\n";
		$log = "";
		
		if($opt == 1)
			$log = "LOG TYPE: MODULE SETTINGS\n" . $timestamp . $data . "\n\n";
		elseif($opt == 2)
			$log = "LOG TYPE: ARTICLE\n" . $data . "\n\n";
		elseif($opt == 3)
			$log = "LOG TYPE: URL\n" . $data . "\n\n";
			
		// This might be useful later
		elseif($opt == 4)
			$log = "LOG TYPE: HTML\n" . $data . "\n\n";
			
		file_put_contents($filename, $log, FILE_APPEND | LOCK_EX);
		chmod($filename, 0775);
	}
}
?>
