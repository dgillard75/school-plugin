<?php

include_once('LMS_Mail.php');
include_once('LMS_PageFunctions.php');
include_once('Page.php');

/**
 * Globals Variables
 */

$student_page = new StudentPage();
if (LMS_PageFunctions::hasFormBeenSubmitted()) {
	$resultArr = $student_page->processForm();
}else{
	$resultArr = $student_page->processGetRequest();
}

$form = $student_page->getFormInformation();    // Initialize Student Account Form
$countryList = $student_page->getCountryList(); //Get Country List for drop down menu
$defaultRedirect = LMS_PageFunctions::getAdminUrlPage(STUDENTS_ADMIN_PAGE,is_SSL());
LMS_PageFunctions::debugPrint($defaultRedirect);
?>

<form method="post"><div id="settings">
<div class="wrap">
	<h2><?php if($form->getField(SAF_ACTION)=='add') : echo 'Add Student'; ?> <?php else : echo 'Edit Student'; endif;?>
	</h2>
</div>
		<?php if (LMS_PageFunctions::hasFormBeenSubmitted()) : ?>
			<div class="updated below-h2" id="message" style="padding:5px; margin:5px 0px">
			<?php if ($form->totalErrors() > 0) : ?>
			<ul>
				<?php if($form->getFormErrorMsg(SAF_DB_ERROR)!=""): ?><?php echo "<li><label for=\"text1\">". $form->getFormErrorMsg(SAF_DB_ERROR) . "</label></li>"?><?php endif; ?>
				<?php if($form->getFormErrorMsg(SAF_FN)!=""): ?><?php echo "<li><label for=\"text1\">". $form->getFormErrorMsg(SAF_FN) . "</label></li>"?><?php endif; ?>
				<?php if($form->getFormErrorMsg(SAF_LN)!=""): ?><?php echo "<li><label for=\"text1\">". $form->getFormErrorMsg(SAF_LN) . "</label></li>"?><?php endif; ?>
				<?php if($form->getFormErrorMsg(SAF_LOGIN)!=""): ?><?php echo "<li><label for=\"text1\">". $form->getFormErrorMsg(SAF_LOGIN) . "</label></li>"?><?php endif; ?>
				<?php if($form->getFormErrorMsg(SAF_EMAIL)!=""): ?><?php echo "<li><label for=\"text1\">". $form->getFormErrorMsg(SAF_EMAIL) . "</label></li>"?><?php endif; ?>
			</ul>
			<?php else : ?>
				<ul>
					<li><label for="text1">Student details successfully updated</label></li>
					<li><label for="text1">Do you want to return the <a href="<?php echo LMS_PageFunctions::getAdminUrlPage(STUDENTS_ADMIN_PAGE,is_SSL());?>">students page.</a></label></li>
				</ul>
			<?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="form-field">
			<label for="user_name">First Name:</label>
			<input type="text" name="<?php echo SAF_FN; ?>" value="<?php echo $form->getField(SAF_FN); ?>" />
		</div>
		<div class="form-field">
			<label for="user_lname">Last Name:</label>
			<input type="text" name="<?php echo SAF_LN; ?>" value="<?php echo $form->getField(SAF_LN); ?>" />
		</div>
		
		<div class="form-field">
			<label for="user_email">Username:</label>
			<?php
			if($form->getField(SAF_ACTION)=='add') : ?> <input type="text" name="<?php echo SAF_LOGIN; ?>" value="<?php echo $form->getField(SAF_LOGIN); ?>" /> <?php  else : echo $form->getField(SAF_LOGIN); ?>
			<input type="hidden" name="<?php echo SAF_LOGIN; ?>" value="<?php echo $form->getField(SAF_LOGIN); endif;?>" />
		</div>
		
		<div class="form-field">
			<label for="user_email">Email:</label>
			<?php
			if($form->getField(SAF_ACTION)=='add') : ?> <input type="text" name="<?php echo SAF_EMAIL; ?>" value="<?php echo $form->getField(SAF_EMAIL); ?>" /> <?php  else : echo $form->getField(SAF_EMAIL); ?>
			<input type="hidden" name="<?php echo SAF_EMAIL; ?>" value="<?php echo $form->getField(SAF_EMAIL); endif;?>" />
			<?php //echo $user_email; ?>
		</div>
		
		<div class="form-field">
			<label for="user_pass">Password:</label>
			<input type="text"  name="<?php echo SAF_PASSWORD; ?>" value="<?php echo $form->getField(SAF_PASSWORD);; ?>" />
		</div>
		<div class="mainpanel">
		<div class="shippingpanel">
		
		<h3>Shipping Address</h3>
			<div>
			<div class="textfieldname">Shipping Address:</div>
			<input type="text" name="<?php echo SAF_SADDRESS; ?>" value="<?php echo $form->getField(SAF_SADDRESS); ?>" class="textfield"/>
		</div>
		<div>
			<div class="textfieldname">Shipping City:</div>
			<input type="text" name="<?php echo SAF_SCITY; ?>" value="<?php echo $form->getFIeld(SAF_SCITY); ?>" class="textfield"/>
		</div>
		<div>
			<div class="textfieldname">Shipping State:</div>
			<input type="text" name="<?php echo SAF_SSTATE; ?>" value="<?php echo $form->getField(SAF_SSTATE); ?>" class="textfield"/>
		</div>
		<div>
						<div class="textfieldname">Shipping Country:</div>
                        <select name="<?php echo SAF_SCOUNTRY;?>" class="textfield" style="width:150px;">
                        	<option value="">Select Country</option>
                            <?php 
							foreach($countryList as $array) {?>
								<option value="<?php  echo $array->name; ?>" <?php if($form->getField(SAF_SCOUNTRY)==$array->name){ ?> selected="selected"<?php } ?>><?php  echo $array->name; ?></option>
							<?php }?>
                        </select>
		</div>
		<div>
			<div class="textfieldname">Shipping Phone:</div>
			<input type="text" name="<?php echo SAF_SPHONE; ?>" value="<?php echo $form->getField(SAF_SPHONE); ?>" class="textfield"/>
		</div>
		<div>
			<div class="textfieldname">Shipping Zipcode:</div>
			<input type="text" name="<?php echo SAF_SZIP; ?>" value="<?php echo $form->getField(SAF_SZIP); ?>" class="textfield"/>
		</div>
		
		</div>
		<div class="billingpanel">
		<h3>Billing Address</h3>
		<div>
			<div class="textfieldname">Billing Address:</div>
			<input type="text" name="<?php echo SAF_BADDRESS;?>" value="<?php echo $form->getField(SAF_BADDRESS); ?>" class="textfield"/>
		</div>
		<div>
			<div class="textfieldname">Billing City:</div>
			<input type="text" name="<?php echo SAF_BCITY;?>" value="<?php echo $form->getField(SAF_BCITY); ?>" class="textfield"/>
		</div>
		<div>
			<div class="textfieldname">Billing State:</div>
			<input type="text" name="<?php echo SAF_BSTATE;?>" value="<?php echo $form->getField(SAF_BSTATE); ?>" class="textfield"/>
		</div>
		<div>
						<div class="textfieldname">Billing Country:</div>
                        <select name="<?php echo SAF_BCOUNTRY; ?>" class="textfield" style="width:150px;">
                        	<option value="">Select Country</option>
                            <?php 
							foreach($countryList as $array) {?>
								<option value="<?php  echo $array->name; ?>" <?php if($form->getField(SAF_BCOUNTRY)==$array->name){ ?> selected="selected"<?php } ?>><?php  echo $array->name; ?></option>
							<?php }?>
                        </select>
			
		</div>
		<div>
			<div class="textfieldname">Billing Phone:</div>
			<input type="text" name="<?php echo SAF_BPHONE; ?>" value="<?php echo $form->getField(SAF_BPHONE); ?>" class="textfield"/>
		</div>
		<div>
			<div class="textfieldname">Billing Zipcode:</div>
			<input type="text" name="<?php echo SAF_BZIP; ?>" value="<?php echo $form->getField(SAF_BZIP); ?>" class="textfield"/>
		</div>
		
		</div>
		</div>
	
		<input type="hidden" name="custom_submit" value="true" />
		<input type="hidden" name="action" value="<?php echo $_GET['action']; ?>" />
		<?php
		if($form->getField(SAF_ACTION)=='edit') : ?>
			<input type="hidden" name="<?php echo SAF_UID ?>" value="<?php echo $form->getField(SAF_UID) ?>" />
		<?php endif; ?>
		<div class="form-field">
		<label for="user_image">&nbsp;</label>
		<p class="submit">
			<input id="submitForm" type="submit" value="<?php if($form->getField(SAF_ACTION)=='add') { echo 'Add user'; } else { echo 'Update user'; } ?>"/>
		</p>
	</div>
</form>
