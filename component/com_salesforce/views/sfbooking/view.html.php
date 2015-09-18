<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');


require_once(JPATH_COMPONENT.DS.'soapclient'.DS.'course.php');
require_once(JPATH_COMPONENT.DS.'assets'.DS.'country.php');

define("CSS", 'components'.DS.'com_salesforce'.DS.'assets'.DS.'css');
define("JS", 'components'.DS.'com_salesforce'.DS.'assets'.DS.'js');
define("IMAGES", 'components'.DS.'com_salesforce'.DS.'assets'.DS.'images');

class SalesforceViewSFBooking extends JView{

  function display($tpl = null)
    {
        $document = &JFactory::getDocument();
        $app = &JFactory::getApplication();
        //$courseList =unserialize($_SESSION['courseList']);
        $courseList = unserialize($app->getUserState( "courseList", null ));
        $courseid = JRequest::getString('courseid',null);
        $selectedCourse = null;
        foreach($courseList as $cor){            
            if($cor->getId() === $courseid){
                $selectedCourse = $cor;
                break;
            }
        }
        $session =&JFactory::getSession();
		$session->set( 'selectedCourse', serialize($selectedCourse) );
        
      $document->addStyleSheet($this->baseurl.DS.CSS .'/bootstrap.min.css');
        $document->addStyleSheet($this->baseurl.DS.CSS.'/font-awesome.min.css');
        $document->addStyleSheet($this->baseurl.DS.CSS.'/salesforce.css');
		$document->addStyleSheet($this->baseurl.DS.CSS.'/bootstrap-theme.min.css');
 		$document->addScript($this->baseurl.DS.JS.'/angular.min.js');
		$document->addScript($this->baseurl.DS.JS.'/app.js');
		 
         
         
        $this->assignRef('selectedCourse', $selectedCourse);
        $this->assignRef('countryList',SFUtil::getCountryList());
        parent::display($tpl);
    }
}