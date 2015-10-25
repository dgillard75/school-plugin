<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
/**
 *
 * Checks to see if is a Form Submission
 * @return bool
 */
function isFormSubmission(){
	$submitted = false;
	if(!empty($_POST["update_order"]))
		$submitted = true;
	return $submitted;
}


$url="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$url1=$url."&action=addNew&redirect=".urlencode($url);


//HANDLE POST REQUEST
if(!empty($_POST["update_order"])) {

	//update order of courses

	//redirect to the REFERRER
	$location = $_SERVER['HTTP_REFERER'].'&msg=order';
	wp_redirect($location);
	exit();
}

//HANDLE GET REQUEST
if(isset($_GET['action'])){
    if ($_GET['action'] == 'add' || $_GET['action'] == 'edit') {
        require('courseform.php');
        //exit();
    } else if ($_GET['action'] == 'delete') {
        require('deletecourse.php');
        exit();
    }
}

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

<?php
	if(isset($_POST) && !empty($_POST)) {
		$courses = $_POST['courses'];
		$orders = $_POST['orders'];
		$counts = sizeof($courses);
		for($i=0; $i<$counts; $i++) {
			mysql_query("UPDATE course SET `tbl_order`=".$orders[$i]." WHERE id=".$courses[$i]);
		}
		$location = $_SERVER['HTTP_REFERER'].'&msg=order';
	?>
		<script type="text/javascript">
		window.location = '<?php echo $location; ?>';
		</script>
	
<?php	
	}
?>
<div class="wrap">
    <div id="icon-edit-pages" class="icon32"></div>
    <h2>My Courses<a class="add-new-h2" href="<?php echo $url1; ?>">Add New</a>	</h2>	
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
					echo "Ordrs updated Successfully"; 
				}
			?>	
		</div>
	<?php } ?>	
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
<?php
	$qer1="select * from course order by tbl_order";
	$re1=mysql_query($qer1);
	while($row1=mysql_fetch_array($re1)) {
	
		echo "<tr>
				<td>
					<input type='text' name='orders[]' value='".$row1['tbl_order']."' style='width:40px'>
					<input type='hidden' name='courses[]' value='".$row1['id']."' style='width:40px'>
				</td>
		<td>".$row1['cname']."
				<div class=\"row-actions\">
					<span class=\"edit\"><a href='".$url1."&courseId=".$row1['id']."'>Edit</a>|</span>
					<span class=\"trash\"><a href='".$url."&delId=".$row1['id']."' onclick='return confirm(\" Are you sure! You want to delete this record \")'>Delete</a> | </span>
					<span class=\"view\"><a rel=\"permalink\" title=\"View\" href=\"admin.php?page=addmodule&courseID=".$row1['id']."\">Modules</a> </span>
					
				</div>
			</td>";
		echo "<td><span class=\"view\"><a rel=\"permalink\" title=\"View\" href=\"admin.php?page=addsets&courseID=".$row1['id']."\">Sets</a></span></td>";
		echo "<td>".$row1['ccode']."</td>";
	    echo "<td>$".$row1['cprice']."</td>";
		echo "<td>$".$row1['discount_price']."</td>";
		if($row1['status'] == 1) { echo "<td>Active</td>"; } else { echo "<td>Inactive</td>"; }	 
		echo "</tr>";
	}
?>
</tbody>
</table>
