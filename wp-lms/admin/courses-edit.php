<?php

require_once(LMS_PLUGIN_PATH."inc/LMS_Defines.php");
require_once(LMS_PLUGIN_PATH."inc/CoursePage.php");
//require_once(LMS_PLUGIN_PATH."inc/HForm.php");

//require_once(LMS_PLUGIN_PATH."inc/CourseHTMLForm.php");


$course_page = new CoursePage();
if (LMS_PageFunctions::hasFormBeenSubmitted()) {
	$resultArr = $course_page->processForm();
}else{
	$resultArr = $course_page->processGetRequest();
}

$courseform = $course_page->getFormInformation();    // Initialize Student Account Form
?>

<form method="POST" action="/school/wp-admin/admin.php?page=WPCW_showPage_ModifyCourse&course_id=10" name="wpcw_course_settings" id="wpcw_course_settings" >

	<div class="wpcw_form_break_tab"></div>
	<div id="settings">
		<div class="wrap">
			<?php if($courseform->getField(CRS_FORM_ACTION) == "add") : $title="Add New Course";?>
			<?php else : $title="Edit Course"; ?>
			<?php endif;?>
			<h2><?php echo $title ?></h2>
	<table class="form-table" id="break_course_general" >
		<tr valign="top" class="form-field">
			<th scope="row"><label for="course_code">Course code<span class="description req"> (required)</span></label></th>
			<td>
				<input type="code" name="<?php echo CRS_FORM_CCODE; ?>" value="<?php echo $courseform->getField(CRS_FORM_CCODE); ?>"  id="course_code" class="lms_course_code "/>
				<span class="setting-description"><br>The Course Code for identification purposes.</span>
			</td>
		</tr>
		<tr valign="top" class="form-field  wpcw_course_settings_course_title_tr">
			<th scope="row"><label for="course_title">Course Title<span class="description req"> (required)</span></label></th>
			<td>
				<input type="title" name="<?php echo CRS_FORM_CNAME; ?>" value="<?php echo $courseform->getField(CRS_FORM_CNAME); ?>"  id="course_title" class="wpcw_course_title "/>
				<span class="setting-description"><br>The title of your course.</span>
			</td>
		</tr>
		<tr valign="top" class="form-field  wpcw_course_settings_course_desc_tr">
			<th scope="row"><label for="course_desc">Course Description<span class="description req"> (required)</span></label></th>
			<td>
				<textarea name="<?php echo CRS_FORM_SHORT_DESC; ?>" rows="5" cols="70" id="course_desc" class="wpcw_course_desc textarea_counter"><?php echo $courseform->getField(CRS_FORM_SHORT_DESC); ?></textarea>
<span id="course_desc_count" class="character_count_area" style="display: none">
												<span class="max_characters" style="display: none">5000</span>
												<span class="count_field"></span> characters remaining
											</span>
				<span class="setting-description"><br>The description of this course. Your trainees will be able to see this course description.</span>
			</td>
		</tr>
		<tr valign="top" class="form-field ">
			<th scope="row"><label for="status">Status<span class="description req"> (required)</span></label></th>
			<td>
				<select name="<?php echo CRS_FORM_STATUS; ?>" >
					<?php $status = $courseform->getField(CRS_FORM_STATUS); ?>
					<option value="1" <?php if($status==''  || $status == 1) { echo "selected='selected'"; } ?> >Active</option>
					<option value="0" <?php if($status!='' && $status == 0) { echo "selected='selected'"; } ?>>Inactive</option>
				</select>
			</td>
		</tr>
		<tr valign="top" class="form-field ">
			<th scope="row"><label for="<?php echo CRS_FORM_CTIME;?>">Course Time<span class="description req"> (required)</span></label></th>
			<td>
			<input type="number" name="<?php echo CRS_FORM_CTIME;?>" value="<?php echo $courseform->getField(CRS_FORM_CTIME);; ?>" size="5" />

			<?php $types = array('Days', 'Months', 'Year'); ?>
			<select name="course_time_type" >
				<?php
				foreach($types as $type) {
					$selected = '';
					if( $type == $courseform->getField(CRS_FORM_COURSE_TIME_TYPE) ) { $selected = 'selected = "selected"'; }
					echo "<option value='$type' $selected>$type</option>";
				}
				?>
			</select>
			</td>
		</tr>
        <tr valign="top" class="form-field ">
            <th scope="row"><label for="<?php echo CRS_FORM_TBL_ORD;?>">Course Order<span class="description req"> (required)</span></label></th>
            <td>
                <input type="number" name="<?php echo CRS_FORM_TBL_ORD;?>" value="<?php echo $courseform->getField(CRS_FORM_TBL_ORD);; ?>" size="5" />
            </td>
        </tr>
	</table>
	<input type="hidden" name="<?php echo CRS_FORM_TERM_ID; ?>" value="<?php echo $courseform->getField(CRS_FORM_TERM_ID); ?>" />
	<input type="hidden" name="<?php echo CRS_FORM_TERM_TAXONOMY; ?>" value="<?php echo $courseform->getField(CRS_FORM_TERM_TAXONOMY); ?>" />
	<input type="hidden" name="<?php echo CRS_FORM_CPRICE; ?>" value="<?php echo $courseform->getField(CRS_FORM_CPRICE); ?>" />
	<input type="hidden" name="<?php echo CRS_FORM_DPRICE; ?>" value="<?php echo $courseform->getField(CRS_FORM_DPRICE); ?>" />
	<input type="hidden" id="course_img" name="<?php echo CRS_FORM_IMG; ?>" value="<?php echo $courseform->getField(CRS_FORM_IMG);; ?>" size="40" />
	<input type="hidden" name="custom_submit" value="true" />
	<input type="hidden" name="redirect" value="<?php echo $_GET['redirect']; ?>" />
            <p class="submit">
                <input class="button-primary" type="submit" name="Submit" value="Save ALL Details" />
                <input type="hidden" name="update" value="wpcw_course_settings" />
                <input type="hidden" name="course_id" value="" />
            </p>
		</div>
	</div>
</form>

