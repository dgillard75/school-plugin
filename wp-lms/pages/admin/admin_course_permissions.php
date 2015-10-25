<?php


require_once(WPLMS_PLUGIN_PATH . "class/htmlform/LMS_CoursePermissionsHTMLForm.class.php");
require_once(WPLMS_PLUGIN_PATH . "class/pages/LMS_CoursePermissionsPage.class.php");

$page = new LMS_CoursePermissionsPage();
if (LMS_PageFunctions::hasFormBeenSubmitted()) {
    $resultArr = $page->processForm();
}else{
    $resultArr = $page->processGetRequest();
}

$show_html = ($resultArr['errors']==0 ? true : false);

if($show_html) {
    $course_list = $page->get_course_list();
    $student_info = $page->get_student_info();
}

$lms_form = $page->getFormInformation();    // Initialize Student Account Form

?>

<div id="settings">
<div class="wrap">
<h2>Update User Course Access Permissions</h2>
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
    <?php endif; ?>

    <?php if($show_html) :  ?>
        <p>Here you can change which courses the user <b><?php echo $student_info["display_name"] ?></b> (Username: <b><?php echo $student_info["user_login"] ?></b>) can access.</p>
    <?php if($show_html && LMS_PageFunctions::hasFormBeenSubmitted()) : ?>
        <div id="message" class="updated fade">
            <p>
                <strong><p>The courses for user <em><?php echo $student_info["display_name"] ?></em> have now been updated.</p>
                    <br>Do you want to return to the <a href="<?php echo LMS_PageFunctions::wp_admin_url()."?page=".WPLMS_ADMIN_STUDENT_PAGE_ID  ?>">Students Summary Page</a>
                </strong>
            </p>
        </div>
    <?php endif; ?>
        <form action="" method="post">
    <table id="wpcw_tbl_course_access_summary" class="widefat wpcw_tbl" class="widefat">
        <thead>
        <tr>
            <th id="allowed_access" scope="col" >Allowed Access</th>
            <th id="course_title" scope="col" >Course Title</th>
            <th id="course_desc" scope="col" >Description</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th id="allowed_access" scope="col" >Allowed Access</th>
            <th id="course_title" scope="col" >Course Title</th>
            <th id="course_desc" scope="col" >Description</th>
        </tr>
        </tfoot>
        <tbody>
        <?php foreach($course_list as $course) : ?>
        <tr class="alternate">
            <td class="allowed_access">
                <input type="checkbox" name="course_list[]" value="<?php echo $course["course_id"]?>" <?php echo in_array('checked',$course) ? 'checked="checked"' : ''; ?> />
            </td>
            <td class="course_title">
                <a href="https://testenv.com/school/wp-admin/admin.php?page=WPCW_showPage_ModifyCourse&course_id=11"><?php echo $course["course_title"]?></a>
            </td>
            <td class="course_desc"><?php echo $course["course_desc"]?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <input type="hidden" name="user_id" value="<?php echo $student_info["user_id"] ?>">
    <input type="submit" class="button-primary" name="wpcw_course_user_access" value="Save Changes" />
</form>
    <?php endif; ?>
</div>
</div>
