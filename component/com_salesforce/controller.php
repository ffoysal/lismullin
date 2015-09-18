<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT.DS.'soapclient'.DS.'sfclient.php');

require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_salesforce'.DS.'classes'.DS.'pdfdocument.php');
require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_salesforce'.DS.'classes'.DS. 'paypal.class.php'); // include the class file

class SalesforceController extends JController{
    
    public function save(){
            $userData = JRequest::get('post');            
            print_r($userData);
            
            /*
             $sf = new SFCourse();
             $acc = $sf->getAccountObject();
             echo '<br/>';
             print_r($acc);
             $dd = $sf->upsertContact();
             echo '<br/>';
             print_r($dd);
             */
    }
    public function userCart(){
        $view = &$this->getView('sfcart', 'html');
		$view->display();
    }
   public function testpaypal(){        
        $p = new paypal_class(); // initiate an instance of the class
        //$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; // testing paypal url
		$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr'; 		//production
		if($p->verifyIPN()){
			$mainframe = JFactory::getApplication();
			$params = JComponentHelper::getParams('com_salesforce');
            $config = &JFactory::getConfig();

            $model = &$this->getModel('paypal');
            if ($model->updatestatusIPN($p->ipn_data['item_number'], $p->ipn_data['payment_amount'])){
                $mailSender = &JFactory::getMailer();
                $mailSender->addRecipient($config->getValue('mailfrom'));
				//$mailSender->addRecipient('foysal.iqbal.fb@gmail.com'));
                $mailSender->addBCC($params->get('component_email'));
				//$mailSender->addBCC('foysal.iqbal.fb@gmail.com');
                $mailSender->setSender(array($config->getValue('mailfrom') , $config->getValue('mailfrom')));
				//$mailSender->setSender(array('info@lismullin.ie' , 'info@lismullin.ie');
                $mailSender->setSubject(JText::_('COM_SALESFORCE_SEND_MSG_IPN_ADMIN_SUBJECT') . ' - ' . $p->ipn_data['transaction_subject'] . ' - ' . $p->ipn_data['last_name']);
                $email_body = sprintf (JText::_('COM_SALESFORCE_SEND_MSG_IPN_ADMIN'));

                foreach ($p->ipn_data as $key => $value){
                    $body .= "\n$key: $value";
                }
                $email_body = $email_body . $body;
                $email_body = html_entity_decode($email_body, ENT_QUOTES);
                $mailSender->setBody($email_body);
                jimport('joomla.utilities.mail');
                if (!$mailSender->Send()){
                    $this->setError(JText::_('COM_SALESFORCE_EMAIL_NOT_SENT'));
                }
            }
 		}else{
				
		}
    }
	
	public function processBooking(){		
		$session =& JFactory::getSession();        
        //contact details is here
		$userData = $session->get('userData');
		
        $totalPrice = $session->get('totalPrice');
		$userData['totalPrice'] = $totalPrice;
		$selectedCourse = unserialize($session->get( 'selectedCourse'));
		
		//selected course external id need to create object
		$courseID = $selectedCourse->getId();//getExternalID();
		//print_r($selectedCourse);
		//Create contact first
		$contactResponse = $this->createContactObject($userData);
		$contactID = $contactResponse['id'];//$contactResponse['Contact_External_ID__c'];
		
		
		$exts = array('crExt'=>$courseID, 'cntExt'=>$contactID);
		$rf = array('Amount_Paid__c'=>$totalPrice, 'Quantity_Registered__c'=>$userData['qty'],'Price_Applicable__c'=>$userData['applicablePrice']);
		$r = $this->createRegistration($exts,$rf);	
		if(! $r->success){
    	$ma = JFactory::getApplication();
        // The order was canceled before being completed.
        $ma->enqueueMessage(JText::_('COM_SALESFORCE_ERROR_PROCESSING_REGISTRATION'));
				//		return $this->setRedirect(JRoute::_('index.php'), JText::_('COM_SALESFORCE_ERROR_PROCESSING_REGISTRATION'));
				return;
			}
			
		
		//$view = $this->getView( 'sfcart', 'html' );		
		//$view->display('paypalintro');
		
		$this->createInvoiceAndEmail($userData,$selectedCourse);
		
		$this->setRedirect(JRoute::_('index.php?option=com_salesforce&view=paypal&registrationid='.$r->id, false),JText::_('COM_SALESFORCE_BOOKING_CONFIRM').' '.$userData['firstname']);
		
	}
	
	private function createInvoiceAndEmail($attendeData, $course){
		$template = $this->getModel('pdftemplate')->getTemplate(1);
		
		$templateData = $this->createTemplateData($attendeData,$course);
		$pdf = new PdfInvoice($template, $templateData);
		$pdf->store(JPATH_ROOT.DS.'invoices'.DS.$templateData['INVOICE_NUMBER'].'.pdf');
		
		$attachment = $pdf->getFile();
		$emailModel = $this->getModel('emailtemplate');
		//$sent = $emailModel->sendemail($templateData, 1, $attachment);
		if (!$emailModel->sendemail($templateData, 1, $attachment))
			return $this->setRedirect(JRoute::_('index.php'), JText::_('COM_SALESFORCE_ERROR_SENDING_EMAILS'));

	}
	
	private function createTemplateData($attendeData, $course){
		
		$invoiceFields = array();
		$invoiceFields['TITLE']=$attendeData['title'];
		$invoiceFields['FIRSTNAME']=$attendeData['firstname'];
		$invoiceFields['LASTNAME']=$attendeData['lastname'];
		$invoiceFields['CUSTOM_COMPANY']=$attendeData['company'];
		$invoiceFields['CUSTOM_STREET']=$attendeData['street'];
		$invoiceFields['CUSTOM_ZIP']=$attendeData['postcode'];
		$invoiceFields['CUSTOM_CITY']=$attendeData['town'];
		$invoiceFields['INVOICE_DATE']= date("d M, Y");
		$invoiceFields['INVOICE_NUMBER']= $this->getInvoiceNumber();
		$invoiceFields['ATTENDEES']=$attendeData['qty'];
		$invoiceFields['COURSE_TITLE']=$course->getTitle();
		$invoiceFields['COURSE_CODE']=$course->getCode();
		$invoiceFields['COURSE_START_DATE']=$course->getStartDate();
		$invoiceFields['COURSE_FINISH_DATE']=$course->getFinishDate();
		$invoiceFields['COURSE_LOCATION']=$course->getLocation();
		$invoiceFields['PRICE_PER_ATTENDEE']= $attendeData['bookPrice'];
		$invoiceFields['PRICE_TOTAL']= $attendeData['totalPrice'];		
		$invoiceFields['CUSTOM_PHONE']= $attendeData['telephone'];
		$invoiceFields['EMAIL']= $attendeData['email'];
		$invoiceFields['SALUTATION']= $attendeData['salutation'];
	  $invoiceFields['QUANTITY']=$attendeData['qty'];
		return $invoiceFields;
	}
	
	private function createRegistration($extids,$data){
		$sf = new SFCourse();
		$resp = $sf->createRegistrationObject($extids,$data);
		return $resp;
	}
	private function createContactObject($data){		
		$sf = new SFCourse();
		$resp = $sf->upsertContact($data);
		return $resp;
	}
	
	
	function getInvoiceNumber()
    {
    	$db = JFactory::getDBO();
    	

    	$db->setQuery('LOCK TABLES `#__salesforce_invoice_number` WRITE');
    	$db->query();

    	$db->setQuery('UPDATE `#__salesforce_invoice_number` SET number=(number+1)');
    	$db->query();
    	
    	$db->setQuery('SELECT number FROM `#__salesforce_invoice_number`');
    	$db->query();
    	$next = $db->loadResult();
    	
    	$db->setQuery('UNLOCK TABLES');
    	$db->query();
    	
    	return 'INV-'.date('Y').'-'.$next;
    }
	public function testReg(){
		$params = JComponentHelper::getParams('com_salesforce');
		echo $params->get('paypal_email');
	}
}
