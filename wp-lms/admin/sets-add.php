ee<?php
	$courseID = '';
	$packageID = '';
	
	if( isset($_GET['courseID']) && !empty($_GET['courseID'])) { 
		$courseID = $_GET['courseID'];		
	} elseif( isset($_GET['packageID']) && !empty($_GET['packageID'])) { 
		$packageID = $_GET['packageID'];
	}	
	
	global $wpdb;
	
	if(isset($_GET['setId']) && !empty($_GET['setId'])) {
		$setId = $_GET['setId'];
		if($courseID != '') {
			$set_qry = mysql_query("SELECT * FROM course_sets WHERE id=".$setId);
		}
		if($packageID != '') {
			$set_qry = mysql_query("SELECT * FROM package_sets WHERE id=".$setId);
		}	

		$set_result = mysql_fetch_array($set_qry);
		$set_name 		= $set_result['set_name'];
		$set_price 		= $set_result['set_price'];
		$discount_price	= $set_result['discount_price'];
		$set_time 		= $set_result['time'];
		$set_time_type  = $set_result['time_type'];
		$modulesArr = array();
		
		if($courseID != '') {
			$set_qry = mysql_query("SELECT * FROM sets_modules WHERE set_id=".$setId);
		}
		if($packageID != '') {
			$set_qry = mysql_query("SELECT * FROM package_set_modules WHERE set_id=".$setId);
		}
		
		while($set_result = mysql_fetch_array($set_qry)) {
			$modulesArr[] = $set_result['module_id'];
		}
		
		
	} else {
		$set_name 		= '';
		$set_price 		= '';
		$discount_price	= '';
		$set_time 		= '';
		$set_time_type  = '';
		$modulesArr = array();
	}

	
	
	
	if (!empty($_POST["custom_submit"]) ) {
		
		$newoptions['set_name'] = strip_tags(stripslashes($_POST["set_name"]));
		$newoptions['set_price'] = strip_tags(stripslashes($_POST["set_price"]));
		$newoptions['discount_price'] = strip_tags(stripslashes($_POST["discount_price"]));
		$newoptions['set_time'] = strip_tags(stripslashes($_POST["set_time"]));
		$newoptions['set_time_type'] = strip_tags(stripslashes($_POST["set_time_type"]));
		$newoptions['courseID'] = $courseID;
		$newoptions['packageID'] = $packageID;
		$allmodules = $_POST['modules'];
		
		if($setId == '') {
			if($courseID != '') {
				$wpdb->insert('course_sets',	array( 
										'set_name' 		=> $newoptions['set_name'],
										'set_price'		=> $newoptions['set_price'],
										'discount_price'=> $newoptions['discount_price'],
										'time' 			=> $newoptions['set_time'],
										'time_type' 	=> $newoptions['set_time_type'],
										'course_id' 	=> $newoptions['courseID']
									)
						);
			}
			if($packageID != '') {
				$wpdb->insert('package_sets',	array( 
										'set_name' 		=> $newoptions['set_name'],
										'set_price'		=> $newoptions['set_price'],
										'discount_price'=> $newoptions['discount_price'],
										'time' 			=> $newoptions['set_time'],
										'time_type' 	=> $newoptions['set_time_type'],
										'package_id' 	=> $newoptions['packageID']
									)
						);
			}	
			
			
			@$lastinsertid = $wpdb->insert_id;
						
			$msg = '&msg=add';
		} else {
			if($courseID != '') {
				mysql_query("UPDATE `course_sets` SET 
					`set_name` 		='".$newoptions['set_name']."',
					`set_price`		='".$newoptions['set_price']."',
					`discount_price`='".$newoptions['discount_price']."',
					`time` 			='".$newoptions['set_time']."',
					`time_type` 	='".$newoptions['set_time_type']."',
					`course_id` 	='".$newoptions['courseID']."'
					WHERE `id`=".$setId);
				mysql_query("DELETE FROM sets_modules WHERE set_id=".$setId);
			}
			if($packageID != '') {
				mysql_query("UPDATE `package_sets` SET 
					`set_name` 		='".$newoptions['set_name']."',
					`set_price`		='".$newoptions['set_price']."',
					`discount_price`='".$newoptions['discount_price']."',
					`time` 			='".$newoptions['set_time']."',
					`time_type` 	='".$newoptions['set_time_type']."',
					`package_id` 	='".$newoptions['packageID']."'
					WHERE `id`=".$setId);
				mysql_query("DELETE FROM package_set_modules WHERE set_id=".$setId);
			}
							
			$lastinsertid = $setId;
			$msg = '&msg=edit';
		} 
		
		if(!empty($allmodules)) {
			if($courseID != '') {
				foreach($allmodules as $module) {
					$wpdb->insert('sets_modules', 	array( 'set_id' => $lastinsertid, 'module_id' => $module));
				}
			}
			if($packageID != '') {
				foreach($allmodules as $module) {
					$wpdb->insert('package_set_modules', 	array( 'set_id' => $lastinsertid, 'module_id' => $module));
				}
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

<form method="post"><div id="settings">
<div class="wrap"><h2><?php if($setId == '') {
	echo '<h2>Add New Set</h2>';
} else {
	echo '<h2>Edit Set</h2>';
} ?></h2>
		<div class="form-field">
			<label for="module_code">Set Name</label>
			<input type="text" name="set_name" value="<?php echo $set_name; ?>" />
		</div>
		<div class="form-field">
			<label for="module_code">Price</label>
			<input type="text" name="set_price" value="<?php echo $set_price; ?>" />
		</div>
		<div class="form-field">
			<label for="module_code">Discount Price</label>
			<input type="text" name="discount_price" value="<?php echo $discount_price; ?>" />
		</div>
		<div class="form-field">
			<label for="module_code">Set Time</label>
			<input type="text" name="set_time" value="<?php echo $set_time; ?>" />
			<?php $types = array('Days', 'Months', 'Year'); ?>
			<select name="set_time_type" >
				<?php 
					foreach($types as $type) {
						$selected = '';
						if( $type == $set_time_type ) { $selected = 'selected = "selected"'; }
						echo "<option value='$type' $selected>$type</option>";
					}
				?> 	
			</select>
		</div>
		<div class="form-field">
			<label for="module_code" style="height:550px;">Select Modules</label>
			
			<?php 
				if($courseID != '') {
					$module_qry = mysql_query("SELECT m.* FROM module m LEFT JOIN module_course mc on mc.module_id = m.module_id WHERE mc.course_id='".$courseID."' order by m.tbl_order");
				}
				if($packageID != '') {
					$module_qry = mysql_query("SELECT m.* FROM module m LEFT JOIN module_package mc on mc.module_id = m.module_id WHERE mc.package_id='".$packageID."' order by m.tbl_order");
				}
				
				while($module_result = mysql_fetch_array($module_qry)) {
						if(in_array($module_result['module_id'],$modulesArr)) {
							$checked = "checked='checked'";
						} else {
							$checked = "";
						}
						echo '<input type="checkbox" name="modules[]" style="width:20px;" value="'.$module_result['module_id'].'" '.$checked.'>'.$module_result['module_name'].'<br/>';
						
				}	
			?>
		</div>
		
		

	<input type="hidden" name="custom_submit" value="true" />
	<input type="hidden" name="redirect" value="<?php echo $_GET['redirect']; ?>" />
	<p class="submit"><input id="submitForm" type="submit" value="<?php if($setId == '') { echo 'Add Set'; } else { echo 'Update Set'; } ?>"></input></p>
	</form>
