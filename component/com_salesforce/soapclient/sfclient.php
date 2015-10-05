<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);
defined('_JEXEC') or die('Access Deny');
/*
define("USERNAME", "XXXXXXXX"); // need to replace with production email
define("PASSWORD", "XXXXXXXX"); //need to replace with production pass
define("SECURITY_TOKEN", "XXXXXXXX"); //need to replace production security tocken
*/
/*
define("USERNAME", "XXXXXXXX"); // need to replace with production email
define("PASSWORD", "XXXXXXXX"); //need to replace with production pass
define("SECURITY_TOKEN", "XXXXXXXX"); //need to replace production security tocken
*/
//require_once ('SforcePartnerClient.php');
require_once ('SforceEnterpriseClient.php');
require_once ('SforceHeaderOptions.php');


require_once('course.php');
require_once('venue.php');

class SFCourse{
    private $mySforceConnection;
	
	public function __construct(){
		$params = JComponentHelper::getParams('com_salesforce');
		
		
		$this->mySforceConnection = new SforceEnterpriseClient();
		//$this->mySforceConnection->createConnection(JPATH_COMPONENT.DS.'soapclient'.DS."production_lismullin.wsdl.xml");
		//$this->mySforceConnection->createConnection(JPATH_ROOT.DS.'components'.DS.'com_salesforce'.DS.'soapclient'.DS."sandbox_lismullin.wsdl.xml");
		$this->mySforceConnection->createConnection(JPATH_ROOT.DS.'components'.DS.'com_salesforce'.DS.'soapclient'.DS.'sys_adm_prod.wsdl.xml');
		
		//$this->mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);
		$this->mySforceConnection->login($params->get('salesforce_email'), $params->get('salesforce_password').$params->get('salesforce_security_token'));
	}
    public function getCourses(){
		$query = "SELECT Id, Name, Type__c, Start_Date__c, Finish_Date__c, Price__c, Venue__c, Course_External_ID__c, RefCouse_Event__c, Price_OAP_Student__c, Description__c, Fully_Booked__c from Course_Event__c WHERE Display_on_website__c='Yes'";
		$response = $this->mySforceConnection->query($query);
	
		$sfCourses = array();
		
		$recordCounter = 0;
		foreach ($response->records as $record) {
			$mycourse = new Course($record->Name,$record->RefCouse_Event__c,$record->Start_Date__c, $record->Finish_Date__c,
								   $this->getVenue($record->Venue__c),$record->Price__c,
								   $record->Price_OAP_Student__c,$record->Description__c, $record->Id, $record->Course_External_ID__c,$record->Type__c, $record->Fully_Booked__c);
								//echo 'Testing.. '.$record->Price_OAP_Student__c;
								
			$sfCourses[$recordCounter] = $mycourse;
			$recordCounter = $recordCounter + 1;			
		}		
        return $sfCourses;
    }
    
	private function getVenue($venueId){
		if(is_null($venueId)){
				return new Venue();
		}
		$res =$this->mySforceConnection->retrieve('Id, Name, Address__c, Phone__c, Website__c','Venue__c',array($venueId));	
		foreach ($res as $rec) {
			
			return new Venue($rec->Name,$rec->Address__c,$rec->Phone__c,$rec->Website__c);
		}
		return new Venue();
	}
	
	public function getAccountObject($actname='Other'){
		$query = "SELECT Id, Name, Account_External_ID__c from Account";
		$response = $this->mySforceConnection->query($query);
		foreach ($response->records as $record) {
			if($record->Name == $actname)
				return $record;
		}
	}
	
	public function upsertContact($contact){
		try{
			$rec = $this->getAccountObject();

			//file_put_contents(JPATH_ROOT.DS."sflog.txt",print_r($rec, true),FILE_APPEND);

			$account = new stdClass();
			$account->Account_External_ID__c= $rec->Account_External_ID__c;//'ACC-EXT0001';//;//'Other'; //array('Id'=>$rec->Id);
			
			$sObject = new stdClass();
			$sObject->FirstName = $contact['firstname'];//'George';
			$sObject->LastName = $contact['lastname'];//'Smith';
			$sObject->MobilePhone = $contact['telephone'];//'510-555-5555';		
			$sObject->Email = $contact['email'];//'test@test.com';
			$sObject->Account= $account;//$rec->Id;
			$sObject->Salutation= $contact['salutation'];
			$sObject->Title= $contact['title'];
			$sObject->LI_Organisation__c= $contact['company'];
			$sObject->LI_Street_Address__c= $contact['street'];
			$sObject->LI_City_County__c= $contact['town'];
			$sObject->LI_Country__c= $contact['country'];
			if(! is_null($contact['postcode'])){
			$sObject->LI_Postal_Code__c= $contact['postcode'];
			}

			//Check if the contact exist or not
			$existedId = $this->isContactExist($contact);
			if(is_null($existedId)){
				$upsertResponse = $this->mySforceConnection->create(array ($sObject), 'Contact');
			}else{
				$sObject->Id = $existedId;
				$upsertResponse = $this->mySforceConnection->update(array ($sObject), 'Contact');				
			}			
			$value = array();
			$value['id'] =$upsertResponse[0]->id;
			$value['success']=$upsertResponse[0]->success;
			
			//file_put_contents(JPATH_ROOT.DS."sflog.txt",print_r($upsertResponse, true),FILE_APPEND);
			return $value;
		}catch(Exception $e){
			$value = array();
			$value['success']=false;
			file_put_contents(JPATH_ROOT.DS."sflog.txt",print_r($e, true),FILE_APPEND);
			return $value;
		}
	}
	private function isContactExist($contact){
		$qr = "SELECT Id, Email,FirstName,LastName from Contact WHERE Email='".$contact['email']."' AND FirstName='".$contact['firstname']."' AND LastName='".$contact['lastname']."' LIMIT 1";
		$res = $this->mySforceConnection->query($qr);
		if($res->size == 1)
			return $res->records[0]->Id;

		return null;		
	}
	
	public function createRegistrationObject($extList,$regFields){
		
		try{
			$records = array();
			$records[0] = new stdClass();
			//$records[0]->Amount_Paid__c = $regFields['Amount_Paid__c'];
			$records[0]->Quantity_Registered__c = $regFields['Quantity_Registered__c'];
			$records[0]->Contact_LI__c =$extList['cntExt'];
			$records[0]->Course_Event__c =$extList['crExt'];
			$records[0]->Price_Applicable__c =$regFields['Price_Applicable__c'];			
			$response = $this->mySforceConnection->create($records,'Registration__c');
			//$response[0]->success=true;
			return 	$response[0];
		}catch(Exception $e){
			//echo 'Caught exception: '.$e->getMessage();
			//$response = array();
			$response[0]->success=false;
			$response[0]->msg=$e;
			file_put_contents(JPATH_ROOT.DS."sflog.txt",print_r($e, true),FILE_APPEND);
			return $response;
		}		
	}
	
	public function updateRegistrationObject($regid,$regFields){
		try{
			$sObject1 = new stdclass();
			$sObject1->Id = $regid;//'a02g000000C6jNzAAJ';//$regid; 
			$sObject1->Amount_Paid__c = $regFields['Amount_Paid__c'];//2300;//
			//$sObject1->Quantity_Registered__c = $regFields['Quantity_Registered__c']; //2;//
			$sObject1->Payment_Method__c = 'Online Payment';
			
			$response = $this->mySforceConnection->update(array ($sObject1), 'Registration__c');
			return true;
		}catch(Exception $e){
			file_put_contents(JPATH_ROOT.DS."sflog.txt",print_r($e, true),FILE_APPEND);
			return false;
		}
	
	}
}
