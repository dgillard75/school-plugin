<?php
/**
 * Get All Courses Available to Student to enroll in. This will exclude the courses the students is currently
 * enrolled in.
 */

ini_set('display_errors', true);
error_reporting(E_ALL);
require_once(LMS_INCLUDE_DIR."ProductListing.php");


if(LMS_PageFunctions::hasFormBeenSubmitted()) :
	/**
	 * Mandatory items:
	 *  - course_id  (Course ID)
	 *  - course_type (Course Type [full,set]
	 *  - uid (User ID)
	 * */

	LMS_SchoolDB::debug($_GET);
	LMS_SchoolDB::debug($_POST);

	if (!empty($_POST['course_id']) && !empty($_POST['course_type']) && !empty($_GET['userid'])) {
        if($_POST['course_type']=="set"){
			$result = LMS_SchoolDB::add_course_to_student_account($_GET["userid"],$_POST['course_id'],$_POST['course_type'],$_POST['setpicked']);
		}else{
			$result = LMS_SchoolDB::add_course_to_student_account($_GET["userid"],$_POST['course_id'],$_POST['course_type']);
		}
	}

	$studentUrl = LMS_PageFunctions::getAdminUrlPage(ADMIN_STUDENTCOURSE_PAGE . "&userid=" . $_GET['userid'], is_SSL());
	$code="add:";
	if($result==ErrorCodeConstants::ALREADY_EXIST){
		$code .= ErrorCodeConstants::ALREADY_EXIST;
		$studentUrl .= "&course=".$_POST['course_id']."&code=".$code;
	}elseif($result==ErrorCodeConstants::FAIL){
		$code .= ErrorCodeConstants::FAIL;
		$studentUrl .= "&code=".$code;
	}else{
		$msg = "Course added";
		$studentUrl .= "&course=".$_POST['course_id']."&code=".$code;
	}

?>
	<script type="text/javascript">
		window.location = '<?php echo $studentUrl; ?>';
	</script>
	<?php exit;?>
<?php else :
	$availCourses = LMS_SchoolDB::get_all_courses_avail_to_student($_GET['userid']);
?>


	<div class="wrap"><h2>Add New Courses </h2></div>
		<div>
		<div class="textfieldname">Courses:</div>
            <select name="courses" class="textfield" style="width:150px;" id="notcourses">
            <option value="">Select Courses</option>
                <?php foreach($availCourses as $usercourse): ?>
					<option value="<?php echo $usercourse->id ?>"> <?php echo $usercourse->cname ?></option>
				<?php endforeach;?>
			</select>
		</div>
		<div>
			<div class="textfieldname">Choose:</div>
            <div id="returnresult"></div>                            
		</div>

<?php endif ?>