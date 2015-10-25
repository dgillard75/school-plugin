<?php

include_once(LMS_INCLUDE_DIR."LMS_PageFunctions.php");
include_once(LMS_INCLUDE_DIR."LMS_SchoolDB.php");

ini_set('display_errors', true);
error_reporting(E_ALL);


$url="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$url1=$url."&action=addNew&redirect=".urlencode($url);


//HANDLE POST REQUEST
if(LMS_PageFunctions::hasFormBeenSubmitted()){

	if(!empty($_POST["update_order"])) {
		$pcourses = $_POST['courses'];
		$porders = $_POST['orders'];
		$pcounts = sizeof($courses);
		for($i=0; $i<$pcounts; $i++) {
			LMS_SchoolDB::update_order_of_courses($porders[$i],$pcourses[$i]);
		}
		//redirect to the REFERRER
		$location = $_SERVER['HTTP_REFERER'].'&msg=order';
		wp_redirect($location);
		exit();
	}
}

//HANDLE GET REQUEST
if(isset($_GET['action'])){
    if ($_GET['action'] == 'add' || $_GET['action'] == 'edit') {
        require(LMS_PLUGIN_PATH."admin/courses-edit.php");
        exit();
    } else if ($_GET['action'] == 'delete') {
        require(LMS_PLUGIN_PATH.'admin/deletecourse.php');
        exit();
    }
}

$listofcourses = LMS_SchoolDB::get_all_courses();
$addNewCourseUrl = LMS_PageFunctions::getAdminUrlPage(ADMIN_COURSES_ADD_PAGE,is_SSL());
$editCourseUrl = LMS_PageFunctions::getAdminUrlPage(ADMIN_COURSES_EDIT_PAGE, is_SSL());
$deleteCourseUrl = LMS_PageFunctions::getAdminUrlPage(ADMIN_COURSES_DELETE_PAGE,is_SSL());
$modulesUrl = LMS_PageFunctions::getAdminUrlPage(ADMIN_MODULES_PAGE,is_SSL());
$setsUrl = LMS_PageFunctions::getAdminUrlPage(ADMIN_SETS_PAGE,is_SSL());

/**
if(isset($_GET['delId']) && $_GET['delId'] != '') {
	$term_id = @mysql_result(mysql_query("SELECT `term_id` FROM course WHERE id =".$_GET['delId']),0);

	$query="DELETE FROM course WHERE id =".$_GET['delId'];
	$re1=mysql_query($query);
	wp_delete_term($term_id, 'course');
	
	mysql_query("DELETE FROM `package_course` WHERE `course_id` = ".$_GET['delId']);
	mysql_query("DELETE FROM `module_course` WHERE `course_id` = ".$_GET['delId']);
	mysql_query("DELETE FROM `course_sets` WHERE `course_id` = ".$_GET['delId']);

	
	
	$location = $_SERVER['HTTP_REFERER'].'&msg=del';
?>
	<script type="text/javascript">
	window.location = '<?php echo $location; ?>';
	</script>
	
<?php exit; } ?> **/
?>


<div class="wrap">
    <div id="icon-edit-pages" class="icon32"></div>
    <h2>My Courses<a class="add-new-h2" href="<?php echo $addNewCourseUrl; ?>">Add New</a>	</h2>
    </div>
	<?php if( isset($_GET['msg']) && !empty($_GET['msg'])) { ?>
		<div class="updated below-h2" id="message" style="padding:5px; margin:5px 0px">
			<?php if($_GET['msg'] == 'add') { 
					echo "Course added Successfully"; 
				} elseif($_GET['msg'] == 'edit') {
					echo "Course updated Successfully"; 
				} elseif($_GET['msg'] == 'del') {
					echo "Course deleted Successfully"; 
				} elseif($_GET['msg'] == 'order') {
					echo "Orders updated Successfully";
				}
			?>	
		</div>
	<?php } ?>

<!------------ COURSE PAGE ------------------------------>
<form method="POST" action="/school/wp-admin/admin.php?page=WPCW_showPage_ModifyCourse&course_id=10" name="wpcw_course_settings" id="wpcw_course_settings" >
	<div class="wpcw_form_break_tab"></div>

	<table class="form-table" id="break_course_general" >
		<tr valign="top" class="form-field  wpcw_course_settings_course_title_tr">
			<th scope="row"><label for="course_title">Course Title<span class="description req"> (required)</span></label></th>
			<td>
				<input type="text" name="course_title" value="Healthy Cooking Made Easy"  id="course_title" class="wpcw_course_title "/>
				<span class="setting-description"><br>The title of your course.</span>
			</td>
		</tr>
		<tr valign="top" class="form-field  wpcw_course_settings_course_desc_tr">
			<th scope="row"><label for="course_desc">Course Description<span class="description req"> (required)</span></label></th>
			<td>
				<textarea name="course_desc" rows="5" cols="70" id="course_desc" class="wpcw_course_desc textarea_counter">This course will teach you all that you need to know to maintain a healthy lifestyle including cooking healthy meals for yourself and your employer.  You will learn how to count calories when preparing meals for those on a restricted diet and will be taught to follow guidelines for those with special dietary or medical needs.</textarea>
<span id="course_desc_count" class="character_count_area" style="display: none">
												<span class="max_characters" style="display: none">5000</span>
												<span class="count_field"></span> characters remaining
											</span>
				<span class="setting-description"><br>The description of this course. Your trainees will be able to see this course description.</span>
			</td>
		</tr>
		<tr valign="top" class="form-field  wpcw_course_settings_course_opt_completion_wall_tr">
			<th scope="row"><label for="course_opt_completion_wall">When do users see the next unit on the course?<span class="description req"> (required)</span></label></th>
			<td>


				<div class="radio_item course_opt_completion_wall-all_visible"><label><input type="radio" name="course_opt_completion_wall" class=" " value="all_visible" style="width: auto;">&nbsp;&nbsp;<b>All Units Visible</b> - All units are visible regardless of completion progress.</label>
				</div>
				<div class="radio_item course_opt_completion_wall-completion_wall"><label><input type="radio" name="course_opt_completion_wall" class=" " value="completion_wall" checked="checked" style="width: auto;">&nbsp;&nbsp;<b>Only Completed/Next Units Visible</b> - Only show units that have been completed, plus the next unit that the user can start.</label>
				</div>

				<span class="setting-description"><br>Can a user see all possible course units? Or must they complete previous units before seeing the next unit?</span>
			</td>
		</tr>
	</table>
</form>

<!------
<form action="" method="post">
	<p>
		<input type="hidden" name="update_order" value="true" />
		<input type="submit" value="Update Order" />
	</p>
<table class="widefat">
<thead>
    <tr>
		<th>Order</th>
        <th>Course Name</th>      
        <th>Sets</th>      
        <th>Course Code</th>
		<th>Course Price</th>
		<th>Discount Price</th>
		<th>Status</th>
	</tr>
</thead>
<tfoot>
    <tr>
		<th>Order</th>
        <th>Course Name</th>      
        <th>Sets</th>      
        <th>Course Code</th>
		<th>Course Price</th>
		<th>Discount Price</th>
		<th>Status</th>
    </tr>
</tfoot>
<tbody>
<?php foreach ($listofcourses as $row1) : ?>
		<tr>
			<td>
				<input type='text' name='orders[]' value='<?php echo $row1->tbl_order; ?>' style='width:40px'>
				<input type='hidden' name='courses[]' value='<?php echo $row1->id; ?>' style='width:40px'>
			</td>
			<td><?php echo $row1->cname; ?>
				<div class="row-actions">
					<span class="edit"><a href="<?php echo $editCourseUrl."&courseID=".$row1->id; ?>">Edit</a>|</span>
					<span class="trash"><a href="<?php echo $deleteCourseUrl."&courseID=".$row1->id; ?>" onclick='return confirm(\" Are you sure! You want to delete this record \")'>Delete</a> | </span>
					<span class="view"><a rel="permalink" title="View" href="<?php echo $modulesUrl."&courseID=".$row1->id; ?>">Modules</a> </span>
				</div>
			</td>
		<td><span class="view"><a rel="permalink" title="View" href="<?php echo $setsUrl."&action=show&courseID=".$row1->id; ?>">Sets</a></span></td>
		<td><?php echo $row1->ccode; ?></td>
	    <td><?php echo $row1->cprice; ?></td>
		<td><?php echo $row1->discount_price; ?></td>
		<?php if($row1->status == 1) : $status="Active"; else : $status="Inactive"; endif;?>
		<td><?php echo $status ?></td>
<?php endforeach; ?>
</tbody>
</table>
--------------->