<?php

include_once(WPLMS_PLUGIN_PATH . "class/LMS_PageFunctions.class.php");
include_once(WPLMS_PLUGIN_PATH . "class/LMS_DBFunctions.class.php");
require_once(WPLMS_PLUGIN_PATH . "class/pages/LMS_ModulePage.class.php");

ini_set('display_errors', true);
error_reporting(E_ALL);

$module_page = new LMS_ModulePage();
if (LMS_PageFunctions::hasFormBeenSubmitted()) {
	$resultArr = $module_page->processForm();
}else{
	$resultArr = $module_page->processGetRequest();
}

$mform = $module_page->getFormInformation();   //Init Module Page Information

$listofcourses = LMS_DBFunctions::get_all_courses();  // Get List of courses

if($mform->getField(LMS_HTML_FORM_ACTION) == "edit") {
	$listofunits = LMS_DBFunctions::retrieve_lesson_by_module($mform->getField(MODULES_TBL_MODULE_ID));
	$title = "Edit Module";
	$provision_message = "Module details updated successfully.";
}else{
	$title = "Add Module";
	$provision_message = "Module details successfully created.";
}

$course_id_for_module = $mform->getField(MODULES_TBL_COURSE_ID);
?>

<h2><?php echo $title; ?></h2>
<?php if(LMS_PageFunctions::hasFormBeenSubmitted()) : ?>
	<?php if($resultArr['errors']!=0) : ?>
		<div id="message" class="error">
			<p>
				<?php if($resultArr['etype']=="db") : ?>
				<strong>
					Sorry, but unfortunately there were some errors saving the module details. Database Errors, please resolve with adminstrator..<br/><br/>
				</strong>
				<?php else : ?>
				<strong>Sorry, but unfortunately there were some errors saving the module details. Please fix the errors and try again.<br/><br/>
					<ul style="margin-left: 20px; list-style-type: square;">
						<?php LMS_Log::print_r($mform->getFormErrors());  foreach($mform->getFormErrors() as $key => $value) : ?>
							<li><?php echo $mform->getFormErrorMsg($key) ?></li>
						<?php endforeach; ?>
					</ul>
				</strong>
				<?php endif; ?>
			</p>
		</div>
	<?php else : ?>
		<div id="message" class="updated fade">
			<p>
				<strong><?php echo $provision_message ?> <br>
					<br>Do you want to return to the <a href="<?php echo WPLMS_DASHBOARD_URL ?>">course summary page</a>?
				</strong>
			</p>
		</div>
	<?php endif; ?>
<?php endif; ?>
<div class="postbox-container" style="width:75%; margin-right: 20px;">
	<div class="metabox-holder">
		<div class="meta-box-sortables">

			<form method="POST" action="" >


				<table class="form-table" >
					<tr valign="top" class="form-field ">
						<th scope="row"><label for="<?php echo MODULES_TBL_MODULE_NUMBER; ?>">Module Number<span class="description req"> (required)</span></label></th>
						<td>
							<input type="number" name="<?php echo MODULES_TBL_MODULE_NUMBER; ?>" value="<?php echo $mform->getField(MODULES_TBL_MODULE_NUMBER); ?>" size="5" />
							<span class="setting-description"><br>The number of your module within the course. This will dictate order.</span>
						</td>
					</tr>
					<tr valign="top" class="form-field  wpform_module_title_tr">
						<th scope="row"><label for="<?php echo MODULES_TBL_MODULE_NAME; ?>">Module Title<span class="description req"> (required)</span></label></th>
						<td>
							<input type="text" name="<?php echo MODULES_TBL_MODULE_NAME; ?>" value="<?php echo $mform->getField(MODULES_TBL_MODULE_NAME); ?>"  id="<?php echo MODULES_TBL_MODULE_NAME; ?>" class="wpcw_module_title "/>
							<span class="setting-description"><br>The title of your module. You <b>do not need to number the modules</b> - this is done above.</span>
						</td>
					</tr>
					<tr valign="top" class="form-field  wpform_parent_course_id_tr">
						<th scope="row"><label for="parent_course_id">Associated Course<span class="description req"> (required)</span></label></th>
						<td>
							<select name="<?php echo MODULES_TBL_COURSE_ID; ?>" class="wpcw_associated_course " id="parent_course_id">
								<?php if($course_id_for_module==""):?>
									<option value="0" selected="selected">-- Select a Training Course --&nbsp;&nbsp;</option>
								<?php else : ?>
									<option value="0">-- Select a Training Course --&nbsp;&nbsp;</option>
								<?php
								endif;
								foreach ($listofcourses as $course) :
									if($course->course_id == $course_id_for_module ){
										$selected_text = "selected=\"selected\"";
									}else{
										$selected_text = "";
									}
									?>
									<option value="<?php echo $course->course_id;?>" <?php echo $selected_text; ?>><?php echo $course->course_title ?>&nbsp;&nbsp;</option>
								<?php endforeach; ?>
							</select>
							<span class="setting-description"><br>The associated training course that this module belongs to.</span>
						</td>
					</tr>
					<tr valign="top" class="form-field  wpform_module_desc_tr">
						<th scope="row"><label for="module_desc">Module Description<span class="description req"> (required)</span></label></th>
						<td>
							<textarea name="<?php echo MODULES_TBL_SHORT_DESC; ?>" rows="5" cols="70" id="<?php echo MODULES_TBL_SHORT_DESC; ?>" class="wpcw_module_desc textarea_counter"><?php echo $mform->getField(MODULES_TBL_SHORT_DESC); ?></textarea>
<span id="module_desc_count" class="character_count_area" style="display: none">
												<span class="max_characters" style="display: none">5000</span>
												<span class="count_field"></span> characters remaining
											</span>
							<span class="setting-description"><br>The description of this module. Your trainees will be able to see this module description.</span>
						</td>
					</tr>
				</table>
				<p class="submit">
					<input class="button-primary" type="submit" name="Submit" value="Save ALL Details" />
					<input type="hidden" name="update" value="" />
					<input type="hidden" name="module_id" value="<?php echo $mform->getField(MODULES_TBL_MODULE_ID); ?>" />
					<input type="hidden" name="action" value="<?php echo $mform->getField(LMS_HTML_FORM_ACTION); ?>" />
				</p>

			</form>
		</div>
	</div>
</div>
<?php if($mform->getField(LMS_HTML_FORM_ACTION)=="edit") : ?>
<div class="postbox-container" style="width:20%;">
	<div class="metabox-holder">
		<div class="meta-box-sortables">
			<div id="wpcw-deletion-module" class="postbox">
				<div class="handlediv" title="Click to toggle"><br /></div>
				<h3 class="hndle"><span>Delete Module?</span></h3>
				<div class="inside">
					<a href="https://testenv.com/school/wp-admin/admin.php?page=WPCW_wp_courseware&action=delete_module&module_id=22" class="wpcw_delete_item" title="Are you sure you want to delete the this module?

This CANNOT be undone!">Delete this Module</a><p>Units will <b>not</b> be deleted, they will <b>just be disassociated</b> from this module.</p>				</div>
			</div>
			<div id="wpcw-units-module" class="postbox">
				<div class="handlediv" title="Click to toggle"><br /></div>
				<h3 class="hndle"><span>Units in this Module</span></h3>
				<div class="inside">
				<ul class="wpcw_unit_list">
				<?php $i=1; foreach($listofunits as $unit) : ?>
					<li>Unit <?php echo $i." - ".$unit['title']; $i++; ?></li>
				<?php endforeach; ?>
			    </ul>
			    </div>
			</div>
		</div>
	</div>
</div>
</div>
<?php endif; ?>