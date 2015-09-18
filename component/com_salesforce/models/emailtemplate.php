<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

//class salesforceModelemailtemplate extends JModel{
class salesforceModelemailtemplate extends JModel{
    
    function sendemail($emaildata, $emailTemplate = 0, $attachment = '')
    {
        $mainframe = JFactory::getApplication();
        $db = &JFactory::getDBO();

        if ($emailTemplate != 0) {
            $emailCond = " AND id=" . $emailTemplate;
        } else {
        	$emailCond = " AND isdefault=1";
        }

        $query = "SELECT * FROM " . $db->nameQuote('#__salesforce_emailtemplate') . " WHERE (templatefor=0".  $emailCond . ")";
        $db->setQuery($query);
        $template = $db->loadObject();
        if ($template) {
            $msgSubject = $template->subject;
            $msgBody = $template->body;
            $msgRecipient = $template->recipient;
            $msgRecipientBCC = $template->bcc;
            
            if (!$this->sendEmailToUserApplication($emaildata, $msgSubject, $msgBody, $msgRecipient, $msgRecipientBCC, $attachment))
            	return false;
            return true;
        }
        return false;
    }
    
    function sendEmailToUserApplication($emaildata, $msgSubject, $msgBody, $msgRecipient, $msgRecipientBCC, $attachment='')
    {
		$params = JComponentHelper::getParams('com_salesforce');
		
    	if (empty($msgRecipient))
    		return False;
    	
        $mainframe = JFactory::getApplication();
        $db = &JFactory::getDBO();
        $mailer = &JFactory::getMailer();
        $config = &JFactory::getConfig();        

        $msgSubject = str_replace('{COURSE_TITLE}', $emaildata['COURSE_TITLE'], $msgSubject);

        $msgBody = str_replace('{SALUTATION}', $emaildata['SALUTATION'], $msgBody);
        $msgBody = str_replace('{TITLE}', $emaildata['TITLE'], $msgBody);
        $msgBody = str_replace('{LASTNAME}', $emaildata['LASTNAME'], $msgBody);
        $msgBody = str_replace('{CUSTOM_COMPANY}', $emaildata['CUSTOM_COMPANY'], $msgBody);
        $msgBody = str_replace('{FIRSTNAME}', $emaildata['FIRSTNAME'], $msgBody);
        $msgBody = str_replace('{CUSTOM_STREET}', $emaildata['CUSTOM_STREET'], $msgBody);
        $msgBody = str_replace('{CUSTOM_ZIP}', $emaildata['CUSTOM_ZIP'], $msgBody);
        $msgBody = str_replace('{CUSTOM_CITY}', $emaildata['CUSTOM_CITY'], $msgBody);
        $msgBody = str_replace('{CUSTOM_COUNTRY}', $emaildata['CUSTOM_COUNTRY'], $msgBody);
        $msgBody = str_replace('{CUSTOM_PHONE}', $emaildata['CUSTOM_PHONE'], $msgBody);
        $msgBody = str_replace('{COURSE_TITLE}', $emaildata['COURSE_TITLE'], $msgBody);
        $msgBody = str_replace('{COURSE_START_DATE}', $emaildata['COURSE_START_DATE'], $msgBody);
        $msgBody = str_replace('{COURSE_FINISH_DATE}', $emaildata['COURSE_FINISH_DATE'], $msgBody);
        $msgBody = str_replace('{COURSE_LOCATION}', $emaildata['COURSE_LOCATION'], $msgBody);
        $msgBody = str_replace('{PRICE_TOTAL}', $emaildata['PRICE_TOTAL'], $msgBody);
				$msgBody = str_replace('{QUANTITY}', $emaildata['QUANTITY'], $msgBody);
        
        $msgRecipient = str_replace('{EMAIL}', $emaildata['EMAIL'], $msgRecipient); 
        $msgRecipientBCC = str_replace('{ADMIN_CUSTOM_RECIPIENT}', $params->get('component_email'), $msgRecipientBCC);
        //$msgRecipientBCC = str_replace('{ADMIN_CUSTOM_RECIPIENT}', 'mokul2003@yahoo.com', $msgRecipientBCC);
                 
        $senderEmail = $config->getValue('mailfrom');
        $senderName = $config->getValue('fromname');
        $mailer->addRecipient(array($msgRecipient)); // for now we have only once receipent
        $mailer->addBCC(array($msgRecipientBCC));

        $mailer->setSubject($msgSubject);
        $sender = array($senderEmail, $senderName);
        $mailer->setSender($sender);
        $mailer->IsHTML(true);
        $mailer->Encoding = 'base64';
		$mailer->setBody($msgBody);
        $mailer->AddEmbeddedImage( JPATH_COMPONENT.'/assets/images/lismullin_institute.jpg', 'logo_id', 'logo.jpg', 'base64', 'image/jpeg' );
        if (! empty($attachment))
            $mailer->addAttachment($attachment);

        $sent = $mailer->send();
        return $sent;
            
        
    }


}
