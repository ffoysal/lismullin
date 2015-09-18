<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);
defined('_JEXEC') or die('Access Deny');
jimport('joomla.application.component.controller');
$controller=JController::getInstance('Salesforce');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();