<?php
defined('_JEXEC') or die('Restricted access');

?>
<div class="panel panel-warning">
    <div class="panel-heading">
        <h3 class="panel-title"><b><?php echo $this->selectedCourse->getTitle();?></b></h3>
    </div>
    <div class="panel-body">

            <table class="table">
                <tbody>
                <tr>
                    <td class="course-details-td">Start Date & Time</td>
                    <td><?php echo $this->selectedCourse->getStartDate();?>, <?php echo $this->selectedCourse->getStartTime();?></td>
                </tr>
                 <tr>
                    <td class="course-details-td"> Finish Date & Time</td>
                    <td><?php echo $this->selectedCourse->getFinishDate();?>, <?php echo $this->selectedCourse->getFinishTime();?></td>
                </tr>
                <tr>
                    <td class="course-details-td">Code</td>
                    <td><?php echo $this->selectedCourse->getCode();?></td>
                </tr>
                <tr>
                    <td class="course-details-td">Price</td>
                    <td>&euro; <?php echo $this->selectedCourse->getPrice();?> </td>
                </tr>
                <?php if($this->selectedCourse->getOapPrice() !== '0.00'){ ?>
                <tr>
                    <td class="course-details-td">OAP/Student Price</td>
                    <td>&euro; <?php echo $this->selectedCourse->getOapPrice();?> </td>
                </tr>
                <?php } ?>
                <tr>
                    <td class="course-details-td">Location</td>
                    <td><?php echo $this->selectedCourse->getLocation();?></td>
                </tr>
                <tr>
                    <td class="course-details-td">Description</td>
                    <td><?php echo $this->selectedCourse->getDescription();?></td>
                </tr>
 
                </tbody>
            </table>

    </div>    
</div>
<?php JHTML::_('behavior.formvalidation'); ?>
 
<script type="text/javascript">
window.addEvent('domready', function(){
    document.formvalidator.setHandler('salutation', function (value) {
        return ($('salutation').value != ""); 
    });
});
window.addEvent('domready', function(){
    document.formvalidator.setHandler('country', function (value) {        
        return ($('country').value != ""); 
    });
});
window.addEvent('domready', function(){
    document.formvalidator.setHandler('name', function (value) {        
    regex=/^[\a-zA-Z]+(\s|\.|-)*[\a-zA-Z]*$/i;    
      return regex.test(value);
    });
});

window.addEvent('domready', function(){
    document.formvalidator.setHandler('phone', function (value) {        
    regex=/^[\+]?\d+$/;
      return regex.test(value);
    });
});


</script>
<div class="panel panel-warning">
    <div class="panel-heading">
        <h3 class="panel-title"><b>Booking Form</b></h3>
    </div>
    <div class="panel-body">
        <form  method="POST" name="booking-form" class="form-validate" >
        <table id="booking-data-table">
			<tr>
				<td colspan="2"><span class="disclamir">The personal information submitted on this booking form will 
				only be used to give effect to your request or instructions, and to retain a record for future 
				necessary communications. Information received will not be disclosed to any third party without 
				your permission, unless in accordance with the Data Protection Acts.</span></td>
			</tr>
            <tr>
                <td><b>Booking Price:</b></td>
                <td>
                    <div class="radio">
                        <label><input type="radio" name="bookCategory" value="FULL" checked="checked"><b>&euro; <?php echo $this->selectedCourse->getPrice();?></b> </label>
                    </div>
                    <?php if($this->selectedCourse->getOapPrice() !== '0.00'){ ?>
                    <div class="radio">
                        <label><input type="radio" value="OAP" name="bookCategory">OAP, Student or Unemployed <b>&euro; <?php echo $this->selectedCourse->getOapPrice();?> </b> </label>
                    </div>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
           <tr>
                <td>Number of Booking:</td>
                <td>
                     <select name="qty">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>                          
                    </select>
                </td>
            </tr>            
            <tr>
                <td colspan="2"><h4><b>Attendee Information</b></h4></td>                
            </tr>
            <tr>
                <td>*Salutation:</td>
                <td>
                    <select name="salutation" id="salutation" class="required validate-salutation">
                        <option value="">- Please Chose -</option>
                        <option value="Mr.">Mr.</option>
                        <option value="Mrs.">Mrs.</option>
                        <option value="Ms.">Ms.</option>
                        <option value="Fr.">Fr.</option>                          
                    </select>
                </td>
            </tr>
            <tr>
                <td>*First Name:</td>
                <td><input type="text" class="form-control required validate-name" id="firstname" name="firstname"/></td>
            </tr>
            <tr>
                <td>*Last Name:</td>
                <td><input type="text" class="form-control required validate-name" id="lasttname" name="lastname"/></td>
            </tr>
            <tr>
                <td>*Email:</td>
                <td><input type="text" class="form-control required validate-email" id="email" name="email" size="30"/></td>
            </tr>
            <tr>
                <td colspan="2"><h4><b>Billing Address</b></h4></td>                
            </tr>
            <tr>
                <td>Company:</td>
                <td><input type="text" class="form-control" id="company" name="company"/></td>
            </tr>
            <tr>
                <td>Title / Position:</td>
                <td><input type="text" class="form-control " id="title" name="title"/></td>
            </tr>
            <tr>
                <td>*Street:</td>
                <td><input type="text" class="form-control required" id="street" name="street"/></td>
            </tr>
            <tr>
                <td>*Town/City:</td>
                <td><input type="text" class="form-control required validate-name" id="town" name="town"/></td>
            </tr>
                      <tr>
                <td>Postcode:</td>
                <td><input type="text" class="form-control" id="postcode" name="postcode"/></td>
            </tr
            <tr>
                <td>*Country:</td>
                <td><?php echo $this->countryList; ?> </td>
            </tr>
          <tr>
                <td>*Mobile:</td>
                <td><input type="text" class="form-control  required validate-phone" id="telephone" name="telephone"/></td>
            </tr>                      
            <tr>
                <td colspan="2"><button type="submit" class="button" value="Book">Book Now</button></td>
            </tr>
            
       </table>
        <input type="hidden" name="option" value="com_salesforce" />
        <input type="hidden" name="task" value="userCart" />
        </form>
    </div>    
</div>





