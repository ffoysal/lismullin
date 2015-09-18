<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);

defined('_JEXEC') or die('Access Deny');
jimport('joomla.application.component.controller');

class SalesforceController extends JController{
    function display($cachable = false, $urlparams = false){
		$doc = JFactory::getDocument();
		$doc->addStyleSheet(JURI::root().'administrator/components/com_salesforce/assets/css/com_sales.css');
		JToolBarHelper::Title('Salesforce Joomla Component','salesforce.png');
		
        echo '<h2>Welcome to Joomla Salesforce component</h2>';
        echo '<br/>';
        echo '<h4>Please configure options before use it</h4>';
        
        JToolBarHelper::preferences('com_salesforce');
        
	}
}