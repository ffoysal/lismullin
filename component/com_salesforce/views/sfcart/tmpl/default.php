<?php
defined('_JEXEC') or die('Restricted access');
  $session =& JFactory::getSession();
  $selectedCourse = unserialize($session->get( 'selectedCourse'));
  $session->set('userData',$this->userData);
  $totalPrice = floatval($this->userData['bookPrice']) * intval($this->userData['qty']);  
  $session->set('totalPrice',$totalPrice);
?>
<div class="panel panel-warning">
    <div class="panel-heading">
        <h3 class="panel-title"><b>Please confirm the details of your booking</b></h3>
    </div>
    <div class="panel-body">
        
        
        <div class="panel panel-default">
            <div class="panel-heading"><b>Attendee Information</b></div>
                <table class="table bill-add-tbl">
                    <tr>
                        <td class="lbl">Name:</td>
                        <td><?php echo $this->userData['firstname'].' '.$this->userData['lastname']; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Email:</td>        
                        <td><?php echo $this->userData['email']; ?></td>
                    </tr>
                </table>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><b>Billing Address</b></div>    
                <table class="table bill-add-tbl">         
                    <tr>
                        <td class="lbl">Company:</td>
                        <td><?php echo $this->userData['company']; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Title/Position:</td>
                        <td><?php echo $this->userData['title']; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Street:</td>        
                        <td><?php echo $this->userData['street']; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Town / City:</td>        
                        <td><?php echo $this->userData['town']; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Postcode:</td>        
                        <td><?php echo $this->userData['postcode']; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Country:</td>        
                        <td><?php echo $this->userData['country']; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Mobile:</td>        
                        <td><?php echo $this->userData['telephone']; ?></td>
                    </tr>
                </table>
        </div>
        <table class="table table-bordered tbl-book-detail">
            <thead>
                <tr>
                    <th>Dates</th>
                    <th>Course</th>
                    <th>Qty Booked</th> 
                    <th>Unit Price (EUR)</th>
                    <th>Total Price (EUR)</th>
                </tr>                
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $selectedCourse->getDates(); ?></td>
                    <td><?php echo $selectedCourse->getTitle(); ?></td>
                    <td><?php echo $this->userData['qty']; ?></td>
                    <td>&euro; <?php echo $this->userData['bookPrice']; ?></td>
                    <td>&euro; <?php echo number_format($totalPrice, 2, '.', '');?></td>
                </tr>
            </tbody>
        </table>
        <div>
           <a href="javascript:history.go(-1)" class="btn btn-default edit-btn">Edit</a>
           <a href="index.php" class="btn btn-default">Cancel</a>
           <a href="index.php?option=com_salesforce&task=processBooking" class="button cr-btn">Confirm Registration</a>
        </div> 
    </div>
 
</div>






