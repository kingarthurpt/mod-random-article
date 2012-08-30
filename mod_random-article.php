<?php
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Include the syndicate functions only once
require_once( dirname(__FILE__).DS.'helper.php' );

$articles = modRandomArticleHelper::getArticles( $params );

$i = 0;
foreach($articles as $article) {
	$urls[$i] = modRandomArticleHelper::getUrl( $article );
	$i++;
}

require( JModuleHelper::getLayoutPath( 'mod_random-article' ) ); 
?>