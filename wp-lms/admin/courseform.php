<?php 
	
	if(isset($_GET['courseId']) && !empty($_GET['courseId'])) {
		$courseId 		= $_GET['courseId'];
		$course_qry 	= mysql_query("SELECT * FROM `course` WHERE `id`=$courseId");
		$course_result	= mysql_fetch_array($course_qry);
		
		$course_name 		= $course_result['cname']; 
		$course_code 		= $course_result['ccode'];
		$course_price 		= $course_result['cprice'];
		$discount_price		= $course_result['discount_price'];
		$course_img 		= $course_result['course_img'];
		$course_time 		= $course_result['ctime'];
		$course_time_type 	= $course_result['course_time_type'];
		$short_desc			= $course_result['short_descp'];
		$term_id			= $course_result['term_id'];
		$term_taxonomy_id	= $course_result['term_taxonomy_id'];
		$description		= stripslashes($course_result['course_discription']);
		$status				= $course_result['status'];
		$sort_order 		= $course_result['tbl_order'];
	} else {
		$course_name 		= '';
		$course_code 		= '';
		$course_price 		= '';
		$discount_price 	= '';
		$course_img 		= '';
		$course_time 		= '';
		$course_time_type 	= '';
		$short_desc			= '';
		$term_id			= '';
		$term_taxonomy_id	= '';
		$description		= '';
		$status				= '';
		$sort_order 		= 0;
	}
	
	global $wpdb;
	$tablename =  'course';
	if (!empty($_POST["custom_submit"]) ) {
		
		
		$newoptions['course_name'] = strip_tags(stripslashes($_POST["course_name"]));
		$newoptions['course_code'] = strip_tags(stripslashes($_POST["course_code"]));
		$newoptions['course_price'] = strip_tags(stripslashes($_POST["course_price"]));
		$newoptions['discount_price'] = strip_tags(stripslashes($_POST["discount_price"]));
		$newoptions['course_img'] = strip_tags(stripslashes($_POST["course_img"]));
		$newoptions['course_time'] = strip_tags(stripslashes($_POST["course_time"]));
		$newoptions['course_time_type'] = strip_tags(stripslashes($_POST["course_time_type"]));
		$newoptions['short_desc'] = strip_tags(stripslashes($_POST["short_desc"]));
		$newoptions['description'] = trim(addslashes($_POST["description"]));
		$newoptions['status'] 		= $_POST["status"];
		$newoptions['term_id'] 		= $_POST["term_id"];
		$newoptions['term_taxonomy_id'] = $_POST["term_taxonomy_id"];
		$newoptions['tbl_order'] = $_POST["sort_order"];
		$sql = "SELECT * from course where cname='".$_POST["course_name"]."'";
		$result = mysql_query($sql);

		$c= mysql_fetch_array($result);
	
		
		if($courseId == '') {
		$num= mysql_num_rows($result);
		if($num<1)
		{
			
			$term = wp_insert_term($_POST["course_name"], 'course', $args = array() );
			/* print_r($term);
			echo $term->term_id;
			exit; */
			$wpdb->insert($tablename, array( 
											'cname' 			=> $newoptions['course_name'], 
											'cprice' 			=> $newoptions['course_price'], 
											'ccode' 			=> $newoptions['course_code'],
											'discount_price'	=> $newoptions['discount_price'],
											'course_img'		=> $newoptions['course_img'],
											'ctime' 			=> $newoptions['course_time'],
											'course_time_type' 	=> $newoptions['course_time_type'],
											'short_descp'		=> $newoptions['short_desc'],
											'course_discription'=> $newoptions['description'],
											'term_id' 			=> $term['term_id'],
											'term_taxonomy_id' 	=> $term['term_taxonomy_id'],
											'status'			=> $newoptions['status'],
											'tbl_order'			=> $newoptions['tbl_order']
										)
						);
			@$lastid = $wpdb->insert_id;
			$msg = '&msg=add';
		}
		
		
		} else {
			mysql_query("UPDATE `course` SET 
				`cname` = '".$newoptions['course_name']."',
				`cprice` = '".$newoptions['course_price']."',
				`ccode` = '".$newoptions['course_code']."',
				`discount_price` = '".$newoptions['discount_price']."',
				`course_img` = '".$newoptions['course_img']."',
				`ctime` = '".$newoptions['course_time']."',
				`course_time_type` = '".$newoptions['course_time_type']."', 
				`short_descp`= '".$newoptions['short_desc']."',
				`course_discription`='".$newoptions['description']."',
				`term_id` 			='".$newoptions['term_id']."',
				`term_taxonomy_id` 	='".$newoptions['term_taxonomy_id']."',
				`status`			='".$newoptions['status']."',
				`tbl_order`			='".$newoptions['tbl_order']."'
				WHERE `id`=".$courseId);
			$term = wp_update_term($newoptions["term_id"], 'course', $args = array( 'name' => $newoptions['course_name']));		
			$msg = '&msg=edit';
		}
	
		$location = $_POST['redirect'];
		$location.= $msg;
?>
	<script type="text/javascript">
		window.location = '<?php echo $location; ?>';
	</script>
 
	
	
	<!--<script type="text/javascript">
$(document).ready(function() {   

  $('#extra_data').onclick(function(){
  
     var thought = $("#e_content").val();
                alert(thought);
				//tinyMCE.activeEditor.selection.setContent(thought);

				//tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, 'content' );
				//tinyMCE.activeEditor.selection.setContent(thought);
				
				
</script>-->

	
<?php	
}



?>
<script type="text/javascript">

function insert_values() {
	var modulecontent = '';
	jQuery('.modulelist:checked').each( function() { 
		var chkId = jQuery(this).val();
		var txt = '[Module_'+chkId+']';
		modulecontent = modulecontent + txt;
		jQuery(this).parents( "tr" ).remove();
	});
	
	tinyMCE.activeEditor.selection.setContent(modulecontent);
	
}

jQuery(document).ready(function(){
	jQuery('#insert').click(function(){
		jQuery("#toggle").slideToggle('slow');
    });
});


</script>	
<form method="post"><div id="settings">
<div class="wrap"><h2><?php if($courseId == '') {
	echo '<h2>Add New Course</h2>';
} else {
	echo '<h2>Edit Course</h2>';
} ?></h2>
		
		<div class="form-field">
			<label for="course_code">Course Code:</label>
			<input type="text" name="course_code" value="<?php echo $course_code; ?>" />
		</div>
		
		<div class="form-field">
			<label for="course_code">Course Title:</label>
			<input type="text" name="course_name" value="<?php echo $course_name; ?>" />
		</div>
		
		<div class="form-field">
			<label for="course_code">Course Price:(in $)</label>
			<input type="text" name="course_price" value="<?php echo $course_price; ?>" />
		</div>
		
		<div class="form-field">
			<label for="course_code">Discount Price:(in $)</label>
			<input type="text" name="discount_price" value="<?php echo $discount_price; ?>" />
		</div>
		
		<div class="form-field">
			<label for="course_image">Course Image: </label>
			<input type="text" id="course_img" name="course_img" value="<?php echo $course_img; ?>" size="40" />
			<input id="course_img_button" type="button" value="Upload Image" onclick="jaascript:Uploader('course_img');" style="width:100px;" />
		</div>
		
		<div class="form-field">
			<label for="course_image">Course Time</label>
			<input type="text" name="course_time" value="<?php echo $course_time; ?>" size="20" />

			<?php $types = array('Days', 'Months', 'Year'); ?>
			<select name="course_time_type" >
				<?php 
					foreach($types as $type) {
						$selected = '';
						if( $type == $course_time_type ) { $selected = 'selected = "selected"'; }
						echo "<option value='$type' $selected>$type</option>";
					}
				?> 	
			</select>
		</div>
		
		<div class="form-field">
			<label for="course_short">Short description: </label>
			<textarea name="short_desc"><?php echo $short_desc; ?></textarea>
		</div>
		
		
	  <div id="insert" style="margin-top:0px;margin-right:10px;text-align:center;cursor: pointer;width:24%;border: 1px solid; border-radius:8px 8px 8px 8px; height:16px;margin-top:30px;"  >Insert Modules In Full Description</div>
	 <br>
		<div id="toggle" style="float:left; clear: both; min-width: 32%;width:auto; border: 1px solid; margin-right: 18px; display:none; padding:12px 12px 12px 12px; ">
		<?php
			$moduleresult = mysql_query("SELECT * FROM `module` WHERE module_id in (select  module_id from module_course where course_id='".$courseId."') ORDER BY tbl_order");
			$totalmodule = mysql_num_rows($moduleresult);
				echo "<table>";
				while ($row = mysql_fetch_array($moduleresult)){
				    echo "<tr>";
				    echo "<td><input type='checkbox' name='module[]' class='modulelist' value='".$row['module_id']."'> ".$row['module_name']."</td>";
				    echo "</tr>";
				}
				echo "</table>";
		?>
	
		<input type="button" value="Insert Modules" name="insert" onclick="insert_values()" style="float: right; margin-right: 5px;"/>
		</div>

		
		
		
		 <?php //wp_editor( $content, $extra_data ); ?>
		 
		<div class="form-field">
			<label for="course_full">Full Description: </label>
			<!--<textarea id="tag_description" name="description" cols="40" rows="10" class="wp-editor-area" aria-hidden="true"><?php echo $description; ?></textarea>-->
			    <?php //wp_editor( $content, 'news_text', $settings = array() );
				 $settings = array(
    'textarea_name' => 'description',
    'media_buttons' => true,
    'tinymce' => array(
        'theme_advanced_buttons1' => 'formatselect,|,bold,italic,underline,|,' .
          'bullist,blockquote,|,justifyleft,justifycenter' .
         ',justifyright,justifyfull,|,link,unlink,|' .
        ',spellchecker,wp_fullscreen,wp_adv,showmodule,'
    ));
wp_editor( $description, 'description', $settings ); 
        ?>
		</div>
		<div id="poststuff">
<?php 

//the_editor($content="", $id ="content", "", true);
?>
</div>
		<div class="form-field">
			<label for="course_image">Status</label>
			<select name="status" >
				<option value="1" <?php if($status==''  || $status == 1) { echo "selected='selected'"; } ?> >Active</option>
				<option value="0" <?php if($status!='' && $status == 0) { echo "selected='selected'"; } ?>>Inactive</option>
			</select>
		</div>
		
		<div class="form-field">
			<label for="course_image">Sort Order</label>
			<input type="text" name="sort_order" value=<?php echo $sort_order; ?> />
		</div>
		
	<input type="hidden" name="term_id" value="<?php echo $term_id; ?>" />
	<input type="hidden" name="term_taxonomy_id" value="<?php echo $term_taxonomy_id; ?>" />
	<input type="hidden" name="custom_submit" value="true" />
	<input type="hidden" name="redirect" value="<?php echo $_GET['redirect']; ?>" />
	<div class="form-field">
		<label for="course_image">&nbsp;</label>
		<p class="submit">
			<input id="submitForm" type="submit" value="<?php if($courseId == '') { echo 'Add Course'; } else { echo 'Update Course'; } ?>"/>
		</p>
	</div>
</form>
