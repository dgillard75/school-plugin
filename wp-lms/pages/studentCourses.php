<?php



ini_set('display_errors', true);
error_reporting(E_ALL);

include_once(LMS_INCLUDE_DIR."LMS_PageFunctions.php");
include_once(LMS_INCLUDE_DIR."StudentAccount.php");
include_once(LMS_INCLUDE_DIR."AllStudentCourses.php");





if(!empty($_GET['action'])){
	if ($_GET['action'] == 'add') {
		require(LMS_PLUGIN_PATH.'admin/studentCourses-add.php');
		exit();
	}

	if ($_GET['action'] == 'delete') {
		require(LMS_PLUGIN_PATH.'admin/studentCourses-delete.php');
		exit();
	}
}


if(LMS_PageFunctions::hasFormBeenSubmitted()) {


}


$userid = $_GET['userid'];
$addNewUrl = LMS_PageFunctions::getAdminUrlPage(ADMIN_STUDENTCOURSE_ADD_PAGE.$_GET['userid'],is_SSL());
$deleteUrl = LMS_PageFunctions::getAdminUrlPage(ADMIN_STUDENTCOURSE_DELETE_PAGE."&userid=".$_GET['userid'],is_SSL());

$studentAcct = new StudentAccount($userid);
$studentInfo = $studentAcct->getInfo();
$allstudentcourses = $studentAcct->getAllCourses();
 

$Days = 'day';
$Months = 'month';
$Year	= 'year';
//$url="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//$url3=$url."&pageAction=addMoreCourses&redirect=".urlencode($url);


/**
if($_GET['pageAction']=='addset') {
	$start = date('Y-m-d h:i:s');
	$setInfoQry = mysql_query("SELECT * FROM course_sets WHERE  id=".$_GET['set_id']);
	$setInfoResult = mysql_fetch_array($setInfoQry);
	
	$end = date('Y-m-d h:i:s',strtotime('+'.$setInfoResult['time'].' '.$$setInfoResult['time_type']));
	mysql_query("UPDATE user_courses_sets SET start_date='".$start."', end_date='".$end."', paid_amount=amount+0, status='Active', payment_method ='By Admin' WHERE user_id=".$_GET['userid']." AND set_id=".$_GET['set_id']." AND course_id=".$_GET['course_id']);
?>
	<script type="text/javascript">
		window.location.href = '<?php echo $_SERVER["HTTP_REFERER"]; ?>';
	</script>
<?php
}
*/

?>

<style>
.modulesContainer td {
    border-bottom: 1px solid #666666;
    border-right: 1px solid #666666;
    padding-left: 8px;
}

.modulesContainer th {
    border-bottom: 1px solid #666666;
    border-right: 1px solid #666666;
    padding-left: 8px;
}
</style>

<div class="wrap"><h2>Student - <?php echo $studentInfo['first_name']; ?>
<a class="add-new-h2" href="<?php echo $addNewUrl ?>">Add New Courses</a></h2>
<div style="margin-top:-14px;margin-bottom:15px;"><strong>(<?php echo $studentInfo['user_email']; ?>)</strong></div>

</div>

	<table class="widefat">
		<thead>
    		<tr>
        		<th>Courses</th>
				<th>Start Date</th>
				<th>End Date</th>
				<th>Register For</th>
				<th>Amount Paid</th>
				<th>Details</th>
     		</tr>
		</thead>
		<tfoot>
    		<tr>
 		       <th>Courses</th>
				<th>Start Date</th>
    		    <th>End Date</th>
        		<th>Register For</th>
				<th>Amount Paid</th>
       			<th>Details</th>
    		</tr>
		</tfoot>
		<tbody>
        <?php foreach($allstudentcourses->getCourses() as $scourseObj) : $scourse = $scourseObj->getCourse();  ?>
        <tr>
            <td><?php echo $scourse['course_name']; ?>
                <div class="row-actions"><span class="trash"><a href="<?php echo $deleteUrl."&courseid=".$scourse['course_id']; ?>" onclick='return confirm(" Are you sure! You want to delete this record")'> Delete</a>  </span></div>
            </td>
            <td><?php echo $scourse['start_date']; ?></td>
            <td><?php echo $scourse['end_date']; ?></td>
            <?php if( $scourse['signup_type'] == '0'): $mArr = $scourseObj->getListOfModules(); $firstModule = $mArr[0];?>
            <td><?php echo $firstModule['set_name']; ?></td>
                <td><?php echo $firstModule['paid_amount']; ?></td>
            <?php elseif ( $scourse['signup_type'] == '1'): ?>
                <td><?php echo "Full Package"; ?></td>
                <td><?php echo $scourse['course_price']; ?></td>
            <?php else : ?>
            <td><?php echo "Full Package"; ?></td>
                <td><?php echo $scourse['course_price']; ?></td>
            <?php endif; ?>
			<td>
			<a onclick="expendme('expendModules_<?php echo $scourse['course_id']; ?>')">Details</a>
			</td>
	   </tr>
            <?php endforeach; ?>
		<?php
			//$userCourseResult = $usercourses_result;
			$_SESSION['user_id'] = $_GET['userid'];
		?>
        </tbody>
    </table>

	<script type="text/javascript">
	function expendme(clsName) {
		var cls = '.'+clsName;
		jQuery(cls).slideToggle('slow');
	}
	function addSet(setlink) {
		var r = confirm('DO you really want to add this Set to this User');
		if(r) {
			window.location.href = setlink;
		}
	}
</script>
