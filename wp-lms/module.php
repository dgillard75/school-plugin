<?php
require('conf.php');
$url="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$url1=$url."&action=addNew&redirect=".urlencode($url);
$topicurl=$url."&action=addtopics";
$redirecturl = "&redirect=".urlencode($url);
$quizeurl=$url."&action=addquizes";
if($_GET['action']=='addNew') {
	require( 'addmodule.php' );
	exit();
}
if($_GET['action']=='addtopics') {
	require('topics.php');
	exit();
}
if($_GET['action']=='addquizes') {
	require('quize.php');
	exit();
}
if($_GET['action']=='questions') {
	require('addquestions.php');
	exit();
}

if(isset($_GET['delId']) && $_GET['delId'] != '') {
	$postid_qry = mysql_query("SELECT pageid FROM `module` where `module_id`=".$_GET['delId']);
	$postidrow =@mysql_fetch_array($postid_qry);
	$postid = $postidrow['pageid'];
	wp_delete_post( $postid, $force_delete ); 
	$query="DELETE FROM module WHERE module_id =".$_GET['delId'];
	$re12=mysql_query($modulepackege_query);
	$re1=mysql_query($query);
	$modulecourse_query="DELETE FROM module_course WHERE module_id =".$_GET['delId'];
	$re11=mysql_query($modulecourse_query);
	$modulepackege_query="DELETE FROM module_package WHERE module_id =".$_GET['delId'];
	$location = $_SERVER['HTTP_REFERER'].'&msg=del';
?>
	<script type="text/javascript">
	window.location = '<?php echo $location; ?>';
	</script>
	
<?php exit; } ?>

<?php 
	if(isset($_POST) && !empty($_POST)) {
		$modules = $_POST['modules'];
		$orders = $_POST['orders'];
		$counts = sizeof($modules);
		for($i=0; $i<$counts; $i++) {
			mysql_query("UPDATE module SET `tbl_order`=".$orders[$i]." WHERE module_id=".$modules[$i]);
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
    <h2>Modules<a class="add-new-h2" href="<?php echo $url1; ?>">Add New</a>	</h2>	
    </div>
	<?php if( isset($_GET['msg']) && !empty($_GET['msg'])) { ?>
		<div class="updated below-h2" id="message" style="padding:5px; margin:5px 0px">
			<?php if($_GET['msg'] == 'add') { 
					echo "Module added Successfully"; 
				} elseif($_GET['msg'] == 'edit') {
					echo "Module updated Successfully"; 
				} elseif($_GET['msg'] == 'del') {
					echo "Module deleted Successfully"; 
				} elseif($_GET['msg'] == 'order') {
					echo "Order Updated Successfully"; 
				}
			?>	
		</div>
	<?php } ?>	
	<?php 
		if( isset($_GET['courseID']) && !empty($_GET['courseID'])) { 
			$courseID = $_GET['courseID'];		
		} else {
			$courseID = '';
		}
		
		if( isset($_GET['packageID']) && !empty($_GET['packageID'])) { 
			$packageID = $_GET['packageID'];		
		} else {
			$packageID = '';
		}
	
	?>
<div style="margin-top:20px; float:left">Choose Course : <select onchange="gotocourse(this.value)">
		<option value="0">Select Course</option>
<?php
	$qer1="select * from course";
	$re1=mysql_query($qer1);
	while($row1=@mysql_fetch_array($re1)) {
?>
		<option value="<?php echo $row1['id'];  ?>"><?php echo $row1['cname'];  ?></option>
<?php		
	}
?>
</select>
</div>
<div style="margin-top:20px; float:left">Choose Package : <select onchange="gotopackage(this.value)">
		<option value="0">Select Package</option>
<?php
	$qer1="select * from package";
	$re1=mysql_query($qer1);
	while($row1=@mysql_fetch_array($re1)) {
?>
		<option value="<?php echo $row1['id'];  ?>"><?php echo $row1['package_name'];  ?></option>
<?php		
	}
?>
</select>
</div>

<?php $redirecturl="https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?page=addmodule&'; ?>
	<script type="text/javascript">
	function gotocourse(courseID) {	
		if(courseID ==0) {
			alert('Please choose course');
		} else {
			window.location = '<?php echo $redirecturl; ?>'+'courseID='+courseID;
		}	
	}
	
	
	function gotopackage(courseID) {	
		if(courseID ==0) {
			alert('Please choose Package');
		} else {
			window.location = '<?php echo $redirecturl; ?>'+'packageID='+courseID;
		}	
	}	
	</script>
		
	
<form action="" method="post">
	<p>
		<input type="submit" value="Update Order" />
	</p>	
<table class="widefat">
<thead>
    <tr>
		<th>Order</th>
        <th>Module Name</th>
        <th>Quiz</th>
		<th>Courses</th>
		<th>Packages</th>
		<th>Pages</th>      

       
     </tr>
</thead>
<tfoot>
    <tr>

		<th>Order</th>
        <th>Module Name</th>      
        <th>Quiz</th>      
		<th>Courses</th>
		<th>Packages</th>
		<th>Pages</th>   
    </tr>
</tfoot>
<tbody>
<?php

	$qer1="select * from module order by tbl_order";
if($courseID != '') {
	$qer1="select m.* from module_course mc LEFT JOIN module m ON m.module_id = mc.module_id WHERE mc.course_id=".$courseID." order by m.tbl_order";
}

if($packageID != '') {
	$qer1="select m.* from module_package mp LEFT JOIN module m ON m.module_id = mp.module_id WHERE mp.package_id=".$packageID." order by m.tbl_order";
}

$re1=mysql_query($qer1);

	
while($row1=@mysql_fetch_array($re1)) { if(!empty($row1['module_id'])) {
//echo "SELECT c.* FROM course c LEFT JOIN module_course mc ON mc.course_id = c.id WHERE mc.module_id = '".$row1['module_id']."'";exit;
	$course_qry = mysql_query("SELECT c.* FROM course c LEFT JOIN module_course mc ON mc.course_id = c.id WHERE mc.module_id = ".$row1['module_id']);
	$modulecourses	='';
	while($course_result = @mysql_fetch_array($course_qry)) {
		$modulecourses.= $course_result['cname'].'<br>';
	}
	//echo "SELECT p.* FROM package p LEFT JOIN module_package mp ON mp.package_id = p.id WHERE mp.module_id ='".$row1['module_id']."'";exit;
	$package_qry = mysql_query("SELECT p.* FROM package p LEFT JOIN module_package mp ON mp.package_id = p.id WHERE mp.module_id =".$row1['module_id']);
	$modulepackages = '';
	while($package_result = @mysql_fetch_array($package_qry)) {
		$modulepackages.= $package_result['package_name'].'<br>';
	}
	
	echo "<tr>
	<td>
		<input type='text' name='orders[]' value='".$row1['tbl_order']."' style='width:40px'>
		<input type='hidden' name='modules[]' value='".$row1['module_id']."' style='width:40px'>
	</td>
	<td>".$row1['module_name']."  	
			<div class=\"row-actions\">
				<span class=\"edit\"><a href='".$url1."&moduleId=".$row1['module_id']."'>Edit</a>| </span>
				<span class=\"trash\"><a href='".$url."&delId=".$row1['module_id']."' onclick='return confirm(\" Are you sure! You want to delete this record \")'>Delete</a> </span>
			</div>	  
		</td>";
	  echo "<td><span class=\"edit\"><a href='".$quizeurl."&moduleId=".$row1['module_id']."' target='_blank' >Quiz</a> </span></td>";
	  echo "<td>".$modulecourses."</td>";
	  echo "<td>".$modulepackages."</td>";
	  echo "<td><a href='".$topicurl.'&moduleId='.$row1['module_id']."'>Topics</a></td>
	 
	   </tr>";
	   
		
	  }
}	  
		
    ?>
</tbody>
</table>
</form>
