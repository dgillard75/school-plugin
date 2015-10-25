<?php
/**
**/



ini_set('display_errors', true);
error_reporting(E_ALL);

include_once(LMS_PLUGIN_PATH.'inc/LMS_PageFunctions.php');
include_once(LMS_PLUGIN_PATH.'inc/LMS_SchoolDB.php');


/* 
	Action:
	- 'addnew' - Add New Student, Show Page to add/edit student - editstudent.phpo
	- 'showpackages' - Show Packages of Student - showpackages.php
	- 'showcourses' - Show Course of Student - student_courses.php
	- 'showcourses' - Show Course of Student - studeunt_courses.php
	- default - Show all Students in School
*/
$message = "";
if(!empty($_GET['action'])){
	if($_GET['action']=='add' || $_GET['action']=='edit'){
		require(LMS_PLUGIN_PATH.'admin/students-edit.php');
		exit();
	}else if($_GET['action']=='showpackages') {
		require('showpackages.php');
		exit();
	}else if($_GET['action']=='showcourses') {
		require('student_courses.php');
		exit();
	}else if($_GET['action']=='deletestduent'){
		require('deletestudent.php');
		exit();
	}else if($_GET['action']=='register'){
		if(isset($_GET["user_id"])){
			$result = LMS_SchoolDB::register_student($_GET["user_id"]);
			if($result > 0 ){
				$uaccount = LMS_SchoolDB::get_student($_GET["user_id"]);
				$message = "User ID: ".$_GET["user_id"]." UserName: " . $uaccount["user_login"] . " has been registered.";
			}else{
				$message = "User ID: ".$_GET["user_id"]." could not be registered: Error(" .$result. ")";
			}
		}
	}
}

$searchterm = "";
if (LMS_PageFunctions::hasFormBeenSubmitted()) {
	$searchterm = $_POST['searchterm'];
}

$packagesurl = LMS_PageFunctions::getAdminUrlPage(STUDENT_ADMIN_PACKAGES_PAGE,is_SSL());
$showcoursesurl = LMS_PageFunctions::getAdminUrlPage(ADMIN_STUDENTCOURSE_PAGE,is_SSL());
$addstudenturl =  LMS_PageFunctions::getAdminUrlPage(STUDENT_ADMIN_ADD_PAGE,is_SSL());
$editstudenturl = LMS_PageFunctions::getAdminUrlPage(STUDENT_ADMIN_EDIT_PAGE,is_SSL());
$deletestudenturl = LMS_PageFunctions::getAdminUrlPage(STUDENT_ADMIN_DELETE_PAGE,is_SSL());



?>

<!-- Student PAGE Title With SearchBox -->
<div class="wrap">
    <div id="icon-edit-pages" class="icon32"></div>
    <h2>Students<a class="add-new-h2" href="<?php echo $addstudenturl ?>">Add New</a></h2>
 	<p class="search-box">
		<label for="post-search-input" class="screen-reader-text">Search Student:</label>
		<form action="<?php LMS_PageFunctions::getAdminUrlPage("",is_ssl())?>" method="POST">
		<input type="search" value="" name="searchterm" id="postsearchinput">
		<input type="submit" value="Searchstudent" class="button" id="searchsubmit" name="searchsubmit"></form>
		( Enter Student's First Name / Last Name / Username / Email to Search )
	</p>	
</div>

<form action="" method="post">
	<?php if($message!=""): ?>
	<div class="updated below-h2" id="message" style="padding:5px; margin:5px 0px">
		<?php echo "<li><label for=\"text1\">". $message. "</label></li>"?>
	</div>
	<?php endif; ?>
		<!-- Begin of Table - Display ALL Students -->
<table class="widefat">
<thead>
    <tr>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Username</th>
        <th>Email Id</th>
        <th>Courses</th>
        <th>Packages</th>

     </tr>
</thead>
<tfoot>
    <tr>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Username</th>
        <th>Email Id</th>
        <th>Courses</th>
        <th>Packages</th>
    </tr>
</tfoot>
<tbody>


<?php $allstudents = LMS_SchoolDB::get_all_students($searchterm); ?>
<?php foreach ( $allstudents as $student ): ?>
 	<tr>
		<td><?php echo $student["first_name"] ?>
			<div class="row-actions">
				<span class="edit"><?php echo "<a href='".$editstudenturl."&userId=".$student["wp_user_id"]."'"?>>Edit</a>"?</span>
				<span class="trash"><?php echo "<a href='".$deletestudenturl."&delId=".$student["wp_user_id"]."' onclick='return confirm(\" Are you sure! You want to delete this record \")'>Delete</a>"?></span>
			</div>
		</td>
		<td><?php echo $student["last_name"]  ?></td>
		<td><?php echo $student["user_login"] ?></td>
		<td><?php echo $student["user_email"] ?></td>
		<td><span class="edit"><?php echo "<a href='".$showcoursesurl."&userid=".$student["wp_user_id"]."'>Courses</a>"?></span></td>
		<td><?php echo "<a href='".$packagesurl."&userid=".$student["wp_user_id"]."'>Packages</a>"?></td>
	</tr>
<?php endforeach; ?> <!-- End of While -->
</tbody>
</table>
</form>
<!-- END of Table - Display ALL Students -->