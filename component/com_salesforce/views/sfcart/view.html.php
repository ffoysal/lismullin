<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');


//require_once(JPATH_COMPONENT.DS.'soapclient'.DS.'course.php');
//require_once(JPATH_COMPONENT.DS.'assets'.DS.'country.php');
define("CSS", 'components'.DS.'com_salesforce'.DS.'assets'.DS.'css');
define("JS", 'components'.DS.'com_salesforce'.DS.'assets'.DS.'js');
define("IMAGES", 'components'.DS.'com_salesforce'.DS.'assets'.DS.'images');

class SalesforceViewSFCart extends JView{

  function display($tpl = null)
    {
        $document = &JFactory::getDocument();
        $app = &JFactory::getApplication();
		$userData = JRequest::get('post');            
        
		$session =& JFactory::getSession();
		$selectedCourse = unserialize($session->get( 'selectedCourse'));           
        $bookCat = $userData['bookCategory'];
        if(strcasecmp('FULL', $bookCat) == 0)
        {
            $userData['bookPrice'] = $selectedCourse->getPrice();
            $userData['applicablePrice']='Full';

        }else{
            $userData['bookPrice'] = $selectedCourse->getOapPrice();
            $userData['applicablePrice']='OAP/Student';
        }
        
      $document->addStyleSheet($this->baseurl.DS.CSS .'/bootstrap.min.css');
        $document->addStyleSheet($this->baseurl.DS.CSS.'/font-awesome.min.css');
        $document->addStyleSheet($this->baseurl.DS.CSS.'/salesforce.css');
		$document->addStyleSheet($this->baseurl.DS.CSS.'/bootstrap-theme.min.css');
 		$document->addScript($this->baseurl.DS.JS.'/angular.min.js');
		$document->addScript($this->baseurl.DS.JS.'/app.js');
		 
         
         
        $this->assignRef('userData', $userData);       
        parent::display($tpl);
    }
}