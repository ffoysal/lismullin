<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');


require_once(JPATH_COMPONENT.DS.'soapclient'.DS.'sfclient.php');

define("CSS", 'components'.DS.'com_salesforce'.DS.'assets'.DS.'css');
define("JS", 'components'.DS.'com_salesforce'.DS.'assets'.DS.'js');
define("IMAGES", 'components'.DS.'com_salesforce'.DS.'assets'.DS.'images');

class SalesforceViewCourses extends JView{

  function display($tpl = null)
    {
        $document = &JFactory::getDocument();
        $app = &JFactory::getApplication();
        $params = $app->getParams('com_salesforce');
         
        $sf = new SFCourse();
        $sfcourses = $sf->getCourses();
        $jsarr = array();
        $c = 0;
        foreach ($sfcourses as $cor) {
            $arr = array('code' => $cor->getCode(),'title'=>$cor->getTitle(),'startDate'=>$cor->getStartDate(),
						 'finishDate'=>$cor->getFinishDate(),'location'=>$cor->getLocation(),'price'=>$cor->getPrice(),
						 'book'=>'Book Now','oapPrice'=>$cor->getOapPrice(),'description'=>$cor->getDescription(),'Id'=>$cor->getId(),
						 'bookable'=>$cor->isValid(),'showAnualText'=>$cor->showAnualText(),'showStartedCourseText'=>$cor->showStartedCourseText(),'showFullyBookedText'=>$cor->showFullyBookedText());
            $jsarr[$c++] = $arr;
        }

        //Store the course list into state
        $app->setUserState( "courseList", serialize($sfcourses) );
        //$_SESSION['courseList'] =serialize( $sfcourses);
        
        
        $document->addScriptDeclaration('var sfc = '. json_encode($jsarr).';');
        
        $document->addStyleSheet($this->baseurl.DS.CSS .'/bootstrap.min.css');
        $document->addStyleSheet($this->baseurl.DS.CSS.'/font-awesome.min.css');
        $document->addStyleSheet($this->baseurl.DS.CSS.'/salesforce.css');
		$document->addStyleSheet($this->baseurl.DS.CSS.'/bootstrap-theme.min.css');
 		$document->addScript($this->baseurl.DS.JS.'/angular.min.js');
		$document->addScript($this->baseurl.DS.JS.'/app.js');
         
        $this->assignRef('sfcourses', $sfcourses);
        $this->assignRef('params', $params);

        parent::display($tpl);
    }
}
