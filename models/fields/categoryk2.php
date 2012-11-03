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
 
jimport('joomla.form.formfield');
 
// The class name must always be the same as the filename (in camel case)
class JFormFieldCategoryK2 extends JFormFieldList {
 
        //The field class must know its own type through the variable $type.
        protected $type = 'categoryk2';
 
        public function getLabel() {
                // code that returns HTML that will be shown as the label
                return parent::getLabel();
        }
 
        public function getInput() {
                // code that returns HTML that will be shown as the form field
                return parent::getInput();
        }
        
        protected function getOptions () {
        	// Initialise variables.
			$options = array();
			
			$query = "SELECT * FROM #__extensions WHERE name = 'com_k2';";
			$db =& JFactory::getDBO();
			$db->setQuery($query);
			$k2 = $db->loadObjectList();
			
			if(isset($k2) && $k2[0]->name == 'com_k2') {
				$query = "SELECT * FROM #__k2_categories;";
				$db->setQuery($query);
				$rows = $db->loadObjectList();
			
				foreach ($rows as $category) {
					$options[$category->id] = $category->name;	
				}
			}
			return $options;
        }
}
