<?php
defined('_JEXEC') or die('Restricted access');
$registrationid = JRequest::getVar('registrationid');            
?>



<?php
// Setup class
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'paypal.class.php');  // include the class file
$p = new paypal_class;             // initiate an instance of the class
//$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url

$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

// Get the page/component configuration
$mainframe = JFactory::getApplication();
//$params = &$mainframe->getParams('com_salesforce');
$params = JComponentHelper::getParams('com_salesforce');
//-------USER DATA --------------------------
  $session =& JFactory::getSession();
  $selectedCourse = unserialize($session->get( 'selectedCourse'));
  $userData = $session->get('userData');
  $totalPrice = $session->get('totalPrice');
//--------------------------------------------------------  
$p->add_field('last_name', $userData['lastname']);
$p->add_field('first_name', $userData['firstname']);
$p->add_field('payer_email', $userData['email']);
//$p->add_field('payer_email', 'foysal.iqbal.fb-buyer@gmail.com');
$p->add_field('business', $params->get('paypal_email'));
//$p->add_field('business', 'foysal.iqbal.fb@gmail.com');
$p->add_field('item_number',$registrationid);
$p->add_field('return', $this_script.'?option=com_salesforce&registrationid='.$registrationid.'&task=paypal.success');
$p->add_field('cancel_return', $this_script.'?option=com_salesforce&registrationid='.$registrationid.'&task=paypal.cancel');
//$p->add_field('notify_url', 'http://www.lismullin.ie/index.php?option=com_salesforce&task=testpaypal');
$p->add_field('item_name', $selectedCourse->getTitle());
//$p->add_field('item_number', $this->bookingDetails->bookingid);
$p->add_field('quantity', $userData['qty']);
$p->add_field('amount', $userData['bookPrice']);
//$p->add_field('currency_code', $params->get('currency'));
$p->add_field('currency_code', 'EUR');
?>

<div align="center">Thank you for registering to attend this Lismullin event.
<p>&nbsp;</p>
If you would like to proceed to pay online with Paypal or Credit Card (whether you have a Paypal account or not) then please click the Paypal button below.
<p>&nbsp;</p>
</div>
<div align="center" class="componentheading">
<?php echo $p->get_submit_paypal_html(); ?>
</div>


<div align="center">Otherwise if you wish to pay on the day of the event via cash, credit card or cheque simply <a href="index.php">click here to return to the Lismullin website</a>.
</div>
