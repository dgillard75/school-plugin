
<?php

require_once(LMS_PLUGIN_PATH . "class/pages/LMS_QuizModifyPage.class.php");

$redirecturl= WPLMS_QMODIFY_URL.'&';


$page = new LMS_QuizModifyPage();
if (LMS_PageFunctions::hasFormBeenSubmitted()) {
$resultArr = $page->processForm();
}else{
$resultArr = $page->processGetRequest();
}
$form = $page->getFormInformation();    // Initialize Form

//set title and return message
if($form->getField(LMS_HTML_FORM_ACTION) == "edit") {
$title = "Edit Quiz";
$provision_message = "Quiz details updated successfully.";
}else{
$title = "Add Quiz";
$provision_message = "Quiz details successfully created.";
}

?>
<script type="text/javascript">
	function gotocourse(courseID) {
		if(courseID ==0) {
			alert('Please choose course');
		} else {
			window.location = '<?php echo $redirecturl; ?>'+'courseID='+courseID;
			exit(0);
		}
	}

	function gotomodules(moduleID) {
		if(moduleID ==0) {
			alert('Please choose Module');
		}
	}
</script>

<h2><?php echo $title; ?></h2>
	<?php if($resultArr['errors']!=0) : ?>
		<div id="message" class="error">
			<p>
				<?php if($resultArr['etype']=="db") : ?>
					<strong>
						Sorry, but unfortunately there were some errors saving the module details. Database Errors, please resolve with adminstrator..<br/><br/>
					</strong>
				<?php else : ?>
					<strong>Sorry, but unfortunately there were some errors saving the Question details. Please fix the errors and try again.<br/><br/>
						<ul style="margin-left: 20px; list-style-type: square;">
							<?php foreach($form->getFormErrors() as $key => $value) : ?>
								<li><?php echo $form->getFormErrorMsg($key) ?></li>
							<?php endforeach; ?>
						</ul>
					</strong>
				<?php endif; ?>
			</p>
		</div>
	<?php else : ?>
		<?php if(LMS_PageFunctions::hasFormBeenSubmitted()) : ?>
		<div id="message" class="updated fade">
			<p>
				<strong><?php echo $provision_message ?> <br>
					<br>Do you want to return to the <a href="http://householdstafftraining.com/school/wp-admin/admin.php?page=WPCW_wp_courseware">Quiz summary page</a>?
				</strong>
			</p>
		</div>
		<?php endif?>
	<?php endif; ?>
<div class="postbox-container" style="width:75%; margin-right: 20px;">
	<div class="metabox-holder">
		<div class="meta-box-sortables">

			<form method="POST" action="" >
				<table class="form-table" id="break_course_general" >
					<tr valign="top" class="form-field ">
						<th scope="row"><label for="<?php echo QUIZMODFORM_CID; ?>">Choose Course<span class="description req"> (required)</span></label></th>
						<td>
							<select name="<?php echo QUIZMODFORM_CID?>" onchange="gotocourse(this.value)">
								<option value="0">Select Course</option>
								<?php $courses = $page->get_courses(); foreach($courses as $row1) : ?>
									<?php if($form->getField(QUIZMODFORM_CID) == $row1['course_id'] ) : ?>
										<option value="<?php echo $row1['course_id'];  ?>"  selected='selected'; ><?php echo $row1['course_title'];  ?></option>
									<?else : ?>
										<option value="<?php echo $row1['course_id'];  ?>" ><?php echo $row1['course_title'];  ?></option>
									<?endif; ?>
								<?php endforeach ?>
							</select>

						</td>
					</tr>
					<tr valign="top" class="form-field ">
						<th scope="row"><label for="<?php echo QUIZMODFORM_MID; ?>">Choose Module<span class="description req"> (required)</span></label></th>
						<td>
							<select name="<?php echo QUIZMODFORM_MID?>" onchange="gotomodules(this.value)">
								<option value="0">Select Module</option>
								<?php $modules = $page->get_modules(); foreach($modules as $row1) : ?>
									<?php if($form->getField(QUIZMODFORM_MID) == $row1->module_id ) : ?>
										<option value="<?php echo $row1->module_id;  ?>"  selected='selected'; ><?php echo $row1->module_name;  ?></option>
									<?else : ?>
										<option value="<?php echo $row1->module_id;  ?>" ><?php echo $row1->module_name;  ?></option>
									<?endif; ?>
								<?php endforeach ?>
							</select>
						</td>
					</tr>
					<tr valign="top" class="form-field  wpcw_course_settings_course_title_tr">
						<th scope="row"><label for="<?php echo QUIZMODFORM_TITLE?>">Quiz Title<span class="description req"> (required)</span></label></th>
						<td>
							<input type="title" name="<?php echo QUIZMODFORM_TITLE; ?>" value="<?php echo $form->getField(QUIZMODFORM_TITLE); ?>"  id="course_title" class="wpcw_course_title "/>
							<span class="setting-description"><br>The title of your Quiz.</span>
						</td>
					</tr>
				</table>

				<p class="submit">
					<input class="button-primary" type="submit" name="Submit" value="Save ALL Details" />
					<input type="hidden" name="custom_submit" value="true" />
					<input type="hidden" name="<?php echo LMS_HTML_FORM_ACTION ?>" value="<?php echo $form->getField(LMS_HTML_FORM_ACTION) ?>" />
					<input type="hidden" name="<?php echo QUIZMODFORM_QUIZ_ID ?>" value="<?php echo $form->getField(QUIZMODFORM_QUIZ_ID) ?>" />


				</p>

			</form>
		</div>
	</div>
</div>
<?php if($form->getField(LMS_HTML_FORM_ACTION)=="edit") : ?>
<div class="postbox-container" style="width:20%;">
	<div class="metabox-holder">
		<div class="meta-box-sortables">
			<div id="wpcw-deletion-module" class="postbox">
				<div class="handlediv" title="Click to toggle"><br /></div>
				<h3 class="hndle"><span>Delete Quiz?</span></h3>
				<div class="inside">
					<a href="https://testenv.com/school/wp-admin/admin.php?page=WPCW_wp_courseware&action=delete_module&module_id=22" class="wpcw_delete_item" title="Are you sure you want to delete the this module?

This CANNOT be undone!">Delete this Quiz</a><p>Units will <b>not</b> be deleted, they will <b>just be disassociated</b> from this module.</p>				</div>
			</div>
			<div id="wpcw-units-module" class="postbox">
				<div class="handlediv" title="Click to toggle"><br /></div>
				<h3 class="hndle"><span>Questions in this Module</span></h3>
				<div class="inside">
				<ul class="wpcw_unit_list">
					<?php $listofqs = $page->get_questions();  $i=1; foreach($listofqs as $q) : ?>
					<li><?php echo "<b>Questions".$i." - </b>".$q['question']; $i++; ?></li>
					<?php endforeach; ?>
				</ul>
			    </div>
			</div>
		</div>
	</div>
</div>
</div>
<?php endif; ?>