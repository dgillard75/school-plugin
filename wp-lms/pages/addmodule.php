<?php 
	global $wpdb;
	
	if(isset($_GET['moduleId']) && !empty($_GET['moduleId'])) {
		$moduleId = $_GET['moduleId'];
		
		$module_qry = mysql_query("SELECT * FROM `module` WHERE `module_id`=$moduleId");
		$module_result = mysql_fetch_array($module_qry);
		$module_name = $module_result['module_name']; 
		$order		 = $module_result['tbl_order']; 
		
		
		$course_qry = mysql_query("SELECT * FROM  module_course WHERE module_id = $moduleId");
		$modulecourses = array();
		while($course_result = mysql_fetch_array($course_qry)) {
			$modulecourses[] = $course_result['course_id'];
		}
		
		$package_qry = mysql_query("SELECT * FROM  module_package WHERE module_id = $moduleId");
		$modulepackages = array();
		while($package_result = mysql_fetch_array($package_qry)) {
			$modulepackages[] = $package_result['package_id'];
		}
	} else {
		$module_name 		= '';
		$order				= 0;
		$modulecourses 		= array();
		$modulepackages 	= array();
		
	}

	
	
	
	if (!empty($_POST["custom_submit"]) ) {
		
		$newoptions['module_name'] = strip_tags(stripslashes($_POST["module_name"]));
		$newoptions['order'] = $_POST["order"];
		$allcourses = $_POST["courses"];
		$allpackeges = $_POST["packages"];
		
		if($moduleId == '') {

			$my_post = array(
			  'post_title'    => $newoptions['module_name'],
			  'post_content'  => '',
			  'post_status'   => 'publish',
			  'post_author'   => 1,
			  'post_type'     => 'topic'
			  
			);

			$post_id = wp_insert_post( $my_post, $wp_error );

			$wpdb->insert('module', 	array( 'module_name' => $newoptions['module_name'],'pageid' => $post_id,'tbl_order'=>$newoptions['order']));
			
			@$lastinsertid = $wpdb->insert_id;
						
			$msg = '&msg=add';
		} else {
			$postid_qry = mysql_query("SELECT pageid FROM `module` where `module_id`=".$moduleId);
			$postidrow =mysql_fetch_array($postid_qry);
			
			$my_post = array(
		  				'ID'   => $postidrow['pageid'],
		  				'post_title'    => $newoptions['module_name'],
		  				'post_content'  => '',
		  				'post_status'   => 'publish',
		  				'post_author'   => 1,
		  				'post_type'     => 'topic'
		  			); 
		  	
		  	wp_update_post( $my_post );

			mysql_query("UPDATE `module` SET `module_name` = '".$newoptions['module_name']."', `tbl_order`= '".$newoptions['order']."' WHERE `module_id`=".$moduleId);
			
			$topicQry = mysql_query("SELECT id from tbl_topics WHERE module_id=".$moduleId);
			while($topicresult = mysql_fetch_array($topicQry)) {
					$my_post = array(
		  				'ID'   => $topicresult['id'],
		  				'tax_input'     => array( 'course' => $allcourses, 'package'=>$allpackeges )
		  			); 
			}		
			
			mysql_query("DELETE FROM `module_course` WHERE `module_id`=".$moduleId);
			
			mysql_query("DELETE FROM `module_package` WHERE `module_id`=".$moduleId);
			$lastinsertid = $moduleId;
			$msg = '&msg=edit';
		}
		
		if(!empty($allcourses)) {
			foreach($allcourses as $course) {
				$wpdb->insert('module_course', 	array( 'module_id' => $lastinsertid, 'course_id' => $course));
			}
		}
		
		if(!empty($allpackeges)) {
			foreach($allpackeges as $package) {
				$wpdb->insert('module_package', 	array('module_id' => $lastinsertid, 'package_id' =>$package));
			}
		}
		
	$location = $_POST['redirect'];
	$location.= $msg;
?>
	<script type="text/javascript">
		window.location = '<?php echo $location; ?>';
	</script>
<?php	
}
?>
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
<form method="post"><div id="settings">
<div class="wrap"><h2><?php if($moduleId == '') {
	echo '<h2>Add New Module</h2>';
} else {
	echo '<h2>Edit Module</h2>';
} ?></h2>
		<div class="form-field">
			<label for="module_code">Module Name</label>
			<input type="text" name="module_name" value="<?php echo $module_name; ?>" />
		</div>
		<div class="form-field">
			<label for="module_code">Order</label>
			<input type="text" name="order" value="<?php echo $order; ?>" />
		</div>
		
		<?php if($courseID == '' && $packageID == '') { ?>
		<div class="form-field">
			<table style="width:900px">
				<tr>
					<td style="width:330px"><b>Choose Courses</b></td>
					<td style="width:330px; padding-left:10px;"><b>Choose Packages</b></td>
				</tr>
				<tr>
					<td style="text-align:left; border-right:1px solid #666; padding:10px;">
					<?php	$course_qry = mysql_query("SELECT * FROM `course`"); 
							while($courserow =mysql_fetch_array($course_qry)) {
								if(in_array($courserow['id'],$modulecourses)) {
									$checked = "checked='checked'";
								} else {
									$checked = "";
								}
								echo "<input type='checkbox' name='courses[]' value='".$courserow['id']."' $checked style='float:left;width: 20px;'/>&nbsp; ".$courserow['cname']."<br/>";	
							
							}
					?>
					</td>
					<td style="text-align:left; padding-left:10px;">
						<?php	$pack_qry = mysql_query("SELECT * FROM `package`");
								while($packrow =mysql_fetch_array($pack_qry)) {
									if(in_array($packrow['id'],$modulepackages)) {
										$checked = "checked='checked'";
									} else {
										$checked = "";
									}
									echo "<input type='checkbox' name='packages[]' value='".$packrow['id']."' $checked style='float:left;width: 20px;'/>&nbsp; ".$packrow['package_name']."<br/>";	
							
								}
						?>
					</td>
				</tr>
			</table>
		
</div>
<?php } elseif($packageID != '') { ?>
	<input type="hidden" name="packages[]" value="<?php echo $packageID; ?>" />
<?php } elseif($courseID != '') { ?>
	<input type="hidden" name="courses[]" value="<?php echo $courseID; ?>" />
<?php } ?>

	<input type="hidden" name="custom_submit" value="true" />
	<input type="hidden" name="redirect" value="<?php echo $_GET['redirect']; ?>" />
	<p class="submit"><input id="submitForm" type="submit" value="<?php if($moduleId == '') { echo 'Add Module'; } else { echo 'Update Module'; } ?>"></input></p>
	</form>
