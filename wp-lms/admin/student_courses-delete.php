<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/11/15
 * Time: 3:29 PM
 */

if(!empty($_GET['userid']) && !empty($_GET['courseid']) && !empty($_GET['ucid'])) {
    LMS_SchoolDB::delete_course_from_student_account($_GET['userid'],$_GET['courseid'],$_GET['ucid'] );
}

$code="&code=del:".ErrorCodeConstants::SUCCESS;
$studentUrl = LMS_PageFunctions::getAdminUrlPage(ADMIN_STUDENTCOURSE_PAGE . "&userid=" . $_GET['userid'], is_SSL()).$code;
?>

<script type="text/javascript">
    window.location = '<?php echo $studentUrl; ?>';
</script>