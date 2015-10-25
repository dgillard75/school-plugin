<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 8/13/15
 * Time: 3:04 PM
 */

include_once(LMS_PLUGIN_PATH . "class/LMS_PageFunctions.class.php");
require_once(LMS_PLUGIN_PATH . "class/pages/LMS_AddQuestionPage.class.php");


$page = new LMS_AddQuestionPage();
if (LMS_PageFunctions::hasFormBeenSubmitted()) {
    $resultArr = $page->processForm();
}else{
    $resultArr = $page->processGetRequest();
}
$form = $page->getFormInformation();    // Initialize Form

//set title and return message
if($form->getField(LMS_HTML_FORM_ACTION) == "edit") {
    $title = "Edit Question";
    $provision_message = "Question details updated successfully.";
}else{
    $title = "Add Question";
    $provision_message = "Question details successfully created.";
}

?>

<form method="POST" action="" name="wpcw_course_settings" id="wpcw_course_settings" >

    <div class="wpcw_form_break_tab"></div>
    <div id="settings">
        <div class="wrap">
            <h2><?php echo $title."[".$page->get_quiz_title()."]" ?></h2>
            <?php if(LMS_PageFunctions::hasFormBeenSubmitted()) : ?>
                <?php if($resultArr['errors']!=0) : ?>
                    <div id="message" class="error">
                        <p>
                            <strong>Sorry, but unfortunately there were some errors saving the Question details. Please fix the errors and try again.<br/><br/>
                                <ul style="margin-left: 20px; list-style-type: square;">
                                    <?php foreach($form->getFormErrors() as $key => $value) : ?>
                                        <li><?php echo $form->getFormErrorMsg($key) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </strong>
                        </p>
                    </div>
                <?php else : ?>
                    <div id="message" class="updated fade">
                        <p>
                            <strong><?php echo $provision_message ?> <br>
                                <br>Do you want to return to the <a href="http://householdstafftraining.com/school/wp-admin/admin.php?page=WPCW_wp_courseware">Quiz Summary Page</a>?
                            </strong>
                        </p>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <?php if($resultArr['errors']!=0) : ?>
                    <div id="message" class="error">
                        <p>
                            <ul style="margin-left: 20px; list-style-type: square;">
                                <?php foreach($form->getFormErrors() as $key => $value) : ?>
                                    <li><?php echo $form->getFormErrorMsg($key) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <table class="form-table" id="break_course_general" >
                <tr valign="top" class="form-field ">
                    <th scope="row"><label for="<?php echo QFORM_ORDER; ?>">Question Number<span class="description req"> (required)</span></label></th>
                    <td>
                        <input type="number" name="<?php echo QFORM_ORDER; ?>" value="<?php echo $form->getField(QFORM_ORDER); ?>" size="5" />
                        <span class="setting-description"><br>The number of your question within the quiz. Default value always set to next question #.</span>
                    </td>
                </tr>
                <tr valign="top" class="form-field  wpcw_course_settings_course_desc_tr">
                    <th scope="row"><label for="course_desc">Question: <span class="description req"> (required)</span></label></th>
                    <td>
                        <textarea name="<?php echo QFORM_QUESTION; ?>" rows="5" cols="70" id="course_desc" class="wpcw_course_desc textarea_counter"><?php echo $form->getField(QFORM_QUESTION); ?></textarea>
<span id="course_desc_count" class="character_count_area" style="display: none">
												<span class="max_characters" style="display: none">1000</span>
												<span class="count_field"></span> characters remaining
											</span>
                        <span class="setting-description"><br>Enter in the question.</span>
                    </td>
                </tr>
                <tr valign="top" class="form-field  wpcw_course_settings_course_title_tr">
                    <th scope="row"><label for="course_title">Answer:(A)<span class="description req"> (required)</span></label></th>
                    <td>
                        <input type="title" name="<?php echo QFORM_ANS_A; ?>" value="<?php echo $form->getField(QFORM_ANS_A); ?>"  id="course_title" class="wpcw_course_title "/>
                        <span class="setting-description"><br>Enter in Answer for A.</span>
                    </td>
                </tr>
                <tr valign="top" class="form-field  wpcw_course_settings_course_title_tr">
                    <th scope="row"><label for="course_title">Answer:(B)<span class="description req"> (required)</span></label></th>
                    <td>
                        <input type="title" name="<?php echo QFORM_ANS_B; ?>" value="<?php echo $form->getField(QFORM_ANS_B); ?>"  id="course_title" class="wpcw_course_title "/>
                        <span class="setting-description"><br>Enter in Answer for B.</span>
                    </td>
                </tr>
                <tr valign="top" class="form-field  wpcw_course_settings_course_title_tr">
                    <th scope="row"><label for="course_title">Answer:(C)<span class="description req"> (required)</span></label></th>
                    <td>
                        <input type="title" name="<?php echo QFORM_ANS_C; ?>" value="<?php echo $form->getField(QFORM_ANS_C); ?>"  id="course_title" class="wpcw_course_title "/>
                        <span class="setting-description"><br>Enter in Answer for C.</span>
                    </td>
                </tr>
                <tr valign="top" class="form-field  wpcw_course_settings_course_title_tr">
                    <th scope="row"><label for="course_title">Answer:(D)<span class="description req"> (required)</span></label></th>
                    <td>
                        <input type="title" name="<?php echo QFORM_ANS_D; ?>" value="<?php echo $form->getField(QFORM_ANS_D); ?>"  id="course_title" class="wpcw_course_title "/>
                        <span class="setting-description"><br>Enter in Answer for D.</span>
                    </td>
                </tr>
                <tr valign="top" class="form-field ">
                    <th scope="row"><label for="status">Correct Answer<span class="description req"> (required)</span></label></th>
                    <td>
                        <select name="<?php echo QFORM_CORRECT_ANSWER; ?>" >
                            <?php $ans = $form->getField(QFORM_CORRECT_ANSWER); ?>
                            <option value="0" <?php if($ans=='') { echo "selected='selected'"; } ?> >Select Correct Answer</option>
                            <option value="1" <?php if($ans=='A') { echo "selected='selected'"; } ?>>Answer (A) </option>
                            <option value="2" <?php if($ans=='B') { echo "selected='selected'"; } ?>>Answer (B) </option>
                            <option value="3" <?php if($ans=='C') { echo "selected='selected'"; } ?>>Answer (C) </option>
                            <option value="4" <?php if($ans=='D') { echo "selected='selected'"; } ?>>Answer (D) </option>
                        </select>
                    </td>
                </tr>
            </table>
            <input type="hidden" id="course_img" name="<?php echo CRS_FORM_IMG; ?>" value="<?php echo $form->getField(CRS_FORM_IMG); ?>" size="40" />
            <input type="hidden" name="custom_submit" value="true" />
            <p class="submit">
                <input class="button-primary" type="submit" name="Submit" value="Save ALL Details" />
                <input type="hidden" name="<?php echo LMS_HTML_FORM_ACTION ?>" value="<?php $form->getField(LMS_HTML_FORM_ACTION) ?>" />
                <input type="hidden" name="<?php echo QFORM_QUIZ_ID ?>" value="<?php $form->getField(QFORM_QUIZ_ID) ?>" />
                <input type="hidden" name="<?php echo QFORM_QUESTION_ID ?>" value="<?php $form->getField(QFORM_QUESTION_ID) ?>" />

            </p>
        </div>
    </div>
</form>
