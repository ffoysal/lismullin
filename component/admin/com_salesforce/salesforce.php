<?php
defined('_JEXEC') or die('Access deny');

jimport('joomla.application.component.controller');

$controller = JController::getInstance('Salesforce');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
