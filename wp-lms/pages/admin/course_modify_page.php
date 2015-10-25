<?php

include_once(WPLMS_PLUGIN_PATH . "class/LMS_PageFunctions.class.php");
require_once(WPLMS_PLUGIN_PATH . "class/pages/LMS_CoursePage.class.php");

ini_set('display_errors', true);
error_reporting(E_ALL);

$course_page = new LMS_CoursePage();
if (LMS_PageFunctions::hasFormBeenSubmitted()) {
    $resultArr = $course_page->processForm();
}else{
    $resultArr = $course_page->processGetRequest();
}

$lms_form = $course_page->getFormInformation();    // Initialize Student Account Form
//set title and return message
if($lms_form->getField(LMS_HTML_FORM_ACTION) == "edit") {
    $title = "Edit Course";
    $provision_message = "Course details updated successfully.";
}else{
    $title = "Add Course";
    $provision_message = "Course details successfully created.";
}

?>

    <form method="POST" action="" name="wpcw_course_settings" id="wpcw_course_settings" >
        <div class="wpcw_form_break_tab"></div>
        <div id="settings">
            <div class="wrap">
                <h2><?php echo $title ?></h2>
                <?php if(LMS_PageFunctions::hasFormBeenSubmitted()) : ?>
                <?php if($resultArr['errors']!=0) : ?>
                <div id="message" class="error">
                    <p>
                        <strong>Sorry, but unfortunately there were some errors saving the course details. Please fix the errors and try again.<br/><br/>
                            <ul style="margin-left: 20px; list-style-type: square;">
                                <?php foreach($lms_form->getFormErrors() as $key => $value) : ?>
                                <li><?php echo $lms_form->getFormErrorMsg($key) ?></li>
                                <?php endforeach; ?>
                             </ul>
                        </strong>
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
                <table class="form-table" id="break_course_general" >
                    <tr valign="top" class="form-field  wpcw_course_settings_course_title_tr">
                        <th scope="row"><label for="course_title">Course Title<span class="description req"> (required)</span></label></th>
                        <td>
                            <input type="title" name="<?php echo CRS_FORM_CNAME; ?>" value="<?php echo $lms_form->getField(CRS_FORM_CNAME) ?>"  id="course_title" class="wpcw_course_title "/>
                            <span class="setting-description"><br>The title of your course.</span>
                        </td>
                    </tr>
                    <tr valign="top" class="form-field  wpcw_course_settings_course_desc_tr">
                        <th scope="row"><label for="course_desc">Course Description<span class="description req"> (required)</span></label></th>
                        <td>
                            <textarea name="<?php echo CRS_FORM_COURSE_DESC; ?>" rows="5" cols="70" id="course_desc" class="wpcw_course_desc textarea_counter"><?php echo $lms_form->getField(CRS_FORM_COURSE_DESC); ?></textarea>
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
                                <?php $status = $lms_form->getField(CRS_FORM_STATUS); ?>
                                <option value="1" <?php if($status==''  || $status == 1) { echo "selected='selected'"; } ?> >Active</option>
                                <option value="0" <?php if($status!='' && $status == 0) { echo "selected='selected'"; } ?>>Inactive</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <input type="hidden" id="course_img" name="<?php echo CRS_FORM_IMG; ?>" value="<?php echo $lms_form->getField(CRS_FORM_IMG); ?>" size="40" />
                <input type="hidden" name="custom_submit" value="true" />
                <p class="submit">
                    <input type="hidden" name="<?php echo CRS_FORM_TABLE_ORDER; ?>" value="<?php echo $lms_form->getField(CRS_FORM_TABLE_ORDER) ?>" />
                    <input class="button-primary" type="submit" name="Submit" value="Save ALL Details" />
                    <input type="hidden" name="action" value="<?php echo $lms_form->getField(CRS_FORM_ACTION) ?>" />
                    <input type="hidden" name="<?php echo CRS_FORM_COURSE_ID; ?>" value="<?php echo $lms_form->getField(CRS_FORM_COURSE_ID) ?>" />
                </p>
            </div>
        </div>
    </form>
