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

}
?>