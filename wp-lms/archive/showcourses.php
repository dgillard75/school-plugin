<?php 
require('conf.php');
	global $wpdb;






//$qer1="select stud.*, course.* from tbl_users stud, user_courses course where stud.id='".$_GET['userid']."' and user_id='".$_GET['userid']."'";
$qer1="select * from tbl_users where id='".$_GET['userid']."'";
$rel=mysql_query($qer1);
$row = mysql_fetch_array($rel);
 
$Days = 'day';
$Months = 'month';
$Year	= 'year';
$url="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$url3=$url."&pageAction=addMoreCourses&redirect=".urlencode($url);




if($_GET['pageAction']=='addMoreCourses') {
require('listcourses.php');
//exit();
}
elseif($_GET['pageAction']=='addset') {
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
	
else
{	

if(isset($_POST['update_type']) && !empty($_POST['update_type'])) {
	if($_POST['status_type'] != '') {
		$Days = 'day';
		$Months = 'month';
		$Year	= 'year';
		$end = date('Y-m-d h:i:s',strtotime('+'.$_POST['time'].' '.$$_POST['time_type']));
		$adminemailId = get_option('admin_email');
		if($_POST['update_type'] == 'course') {
			if($_POST['status_type'] == 'Inactive') {
				mysql_query("UPDATE user_courses SET status ='Inactive' WHERE user_id=".$_GET['userid']." AND course_id=".$_POST['course_id']);
			}
			
			if($_POST['status_type'] == 'Active') {
				mysql_query("UPDATE user_courses SET status ='Active', end_date='".$end."'  WHERE user_id=".$_GET['userid']." AND course_id=".$_POST['course_id']);
				
				$cname = @mysql_result(mysql_query("select cname from course where id='".$_POST['course_id']."'"),0);
				/******************* Mail This  *********************/
					$emailId = $row['user_email'];
					$subject = 'Course/Package is Active! Start learning now!';
					$message = '
								<p>Congratulations! You can start leaning now!</p>
								<table style="width:600px;border:1px solid #666" cellpadding="10">
									<tr>
										<td width="100"><strong>Course/Package</strong></td>
										<td>'.$cname.'</td>
									</tr>
									<tr>
										<td width="100"><strong>Registration type:</strong></td>
										<td>Full Course</td>
									</tr>
									<tr>
										<td width="100"><strong>Status:</strong></td>
										<td><strong>Active</strong></td>
									</tr>
					
								</table>
								<p><br/><br/>
									To start leaning, just go to our website <a href="https://www.householdstafftraining.com">www.householdstafftraining.com</a> and login to your account, then click on a Course/Package name and click on "View Topics" link.<br/><br/><br/>
									Good Luck!<br/><br/>
									Regards,<br/>
									House Hold Stuff Online Institute Team<br/>
									<a href="https://www.householdstafftraining.com">www.householdstafftraining.com</a>
								</p>
								
								';

					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: Household Staff Training <'.$adminemailId.'>' . "\r\n";
					
					mail($emailId, $subject, $message, $headers);
					/******************* Mail This  *********************/
			}
			
		}
		
		
		
		if($_POST['update_type'] == 'set') {
			if($_POST['status_type'] == 'Inactive') {
				mysql_query("UPDATE user_courses_sets SET status ='Inactive' WHERE user_id=".$_GET['userid']." AND set_id=".$_POST['set_id']);
			}
			
			if($_POST['status_type'] == 'Active') {
				$qry  = mysql_query("SELECT * FROM course_sets WHERE id=".$_POST['set_id']);
				$rst = mysql_fetch_array($qry);
				mysql_query("UPDATE user_courses SET status ='Active' WHERE user_id=".$_GET['userid']." AND course_id=".$rst['course_id']);
				mysql_query("UPDATE user_courses_sets SET status ='Active', end_date='".$end."'  WHERE user_id=".$_GET['userid']." AND set_id=".$_POST['set_id']);
				
				$cname = @mysql_result(mysql_query("select cname from course where id='".$rst['course_id']."'"),0);
				/******************* Mail This  *********************/
					$emailId = $row['user_email'];
					
					$subject = 'Course/Package is Active! Start learning now!';
					$message = '
								<p>Congratulations! You can start leaning now!</p>
								<table style="width:600px;border:1px solid #666" cellpadding="10">
									<tr>
										<td width="100"><strong>Course/Package</strong></td>
										<td>'.$cname.'</td>
									</tr>
									<tr>
										<td width="100"><strong>Registration type:</strong></td>
										<td>'.$rst['set_name'].'</td>
									</tr>
									<tr>
										<td width="100"><strong>Status:</strong></td>
										<td><strong>Active</strong></td>
									</tr>
					
								</table>
								<p><br/><br/>
									To start leaning, just go to our website <a href="https://www.householdstafftraining.com">www.householdstafftraining.com</a> and login to your account, then click on a Course/Package name and click on "View Topics" link.<br/><br/><br/>
									Good Luck!<br/><br/>
									Regards,<br/>
									House Hold Stuff Online Institute Team<br/>
									<a href="https://www.householdstafftraining.com">www.householdstafftraining.com</a>
								</p>
								
								';

					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: Household Staff Training <'.$adminemailId.'>' . "\r\n";
					
					mail($emailId, $subject, $message, $headers);
					/******************* Mail This  *********************/
				
				
				
			}
		}
	} 
}

if(isset($_GET['delId']) && $_GET['delId'] != '') {
	
	$query="DELETE FROM user_courses WHERE id =".$_GET['delId'];
	$re1=mysql_query($query);
	
	$location = $_SERVER['HTTP_REFERER'].'&msg=del';
?>
	<script type="text/javascript">
	window.location = '<?php echo $location; ?>';
	</script>
	
<?php exit; } ?>
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

<div class="wrap"><h2>Student - <?php echo $row['first_name']; ?>
<a class="add-new-h2" href="<?php echo $url3.'&uid='.$_GET['userid'];?>">Add New Courses</a></h2>
<div style="margin-top:-14px;margin-bottom:15px;"><strong>(<?php echo $row['user_email']; ?>)</strong></div>

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
<?php
 $user_course="select * from user_courses where user_id='".$_GET['userid']."'";
	$uc=mysql_query($user_course);
	while($usercourses_result = mysql_fetch_array($uc))
	{
//echo "select * from course where id='".$usercourses_result['course_id']."'";
$courses=mysql_query("select * from course where id='".$usercourses_result['course_id']."'");
while($courseResult = mysql_fetch_array($courses))
{
?>
	  <tr>
		
			<td><?php echo $courseResult['cname']; ?>
			<div class=\"row-actions\"><span class="trash"><a href='<? echo $url."&delId=".$usercourses_result['id']; ?>' onclick='return confirm(" Are you sure! You want to delete this record")'>Delete</a>  </span></div>
			</td>
			<td><?php echo $usercourses_result['start_date']; ?></td>
			<td><?php echo $usercourses_result['end_date']; ?></td>
			
			<?php if( $usercourses_result['signup_type'] == '0') { ?><td>
			<?php
		 
				$courses_user_sets=mysql_query("select * from user_courses_sets where course_id='".$usercourses_result['course_id']."' and user_id='".$_GET['userid']."' and paid_amount !='0'");
				$courseset_Result = mysql_fetch_array($courses_user_sets);

				$setsQry=mysql_query("select * from  course_sets where id='".$courseset_Result['set_id']."'");
				$set_Result = mysql_fetch_array($setsQry);
				  echo $set_Result['set_name'];
			?> 
			</td>	<?php } 
					else if( $usercourses_result['signup_type'] == '1') 
					{ ?> <td> <?php echo "Full Package"; ?> </td> 
					<?php } ?>
			
			<td><?php if($usercourses_result['paid_amount'] !='') {?>$ <?php echo $usercourses_result['paid_amount']; } ?></td>
			<td>
			<a onclick="expendme('expendModules_<?php echo $usercourses_result['course_id']; ?>')">Details</a>
			</td>
	   </tr>
		<?php
			$userCourseResult = $usercourses_result;
			$_SESSION['user_id'] = $_GET['userid'];
		?>
	   <tr>
		<td colspan="8" class="expendModules_<?php echo $userCourseResult['course_id']; ?>" style="display:none;">
			
				<p>	
							<div>
							
							<table class="modulesContainer" style="border:1px solid #000; width:98%">
								<thead>
									<th>Modules</th>
									<th>Payment</th>
									<th>Status</th>
									<th>Expiration Date</th>
									<th>Quiz Status</th>
									<th>Attempts Left</th>
								</thead>
					<?php
						if($userCourseResult['signup_type']==1) {
							$CourseModulesQry = mysql_query("SELECT m.* FROM module m LEFT JOIN module_course mc ON m.module_id = mc.module_id WHERE mc.course_id=".$userCourseResult['course_id']." ORDER BY m.tbl_order");
							$moduleCount = 1;
							$active = 0;
							$quiz = 0;
							while($CourseModulesResult = mysql_fetch_array($CourseModulesQry)) {
							$pass=0;
					?>
								<tr>
									<td><?php echo $CourseModulesResult['module_name']; ?></td>
									<td><?php echo $userCourseResult['payment_method']; ?></td>
									<td>
									<?php
										if($userCourseResult['status']=='Active') {
											$UserModulesQry = mysql_query("SELECT * FROM user_register_modules WHERE module_id=".$CourseModulesResult['module_id']." AND course_id=".$userCourseResult['course_id']." AND user_id=".$_SESSION['user_id']);
											if(mysql_num_rows($UserModulesQry) >0) {
												$UserModulesResult = mysql_fetch_array($UserModulesQry);
												if($UserModulesResult['passed']=='y') {
													$pass = 1;
													echo "Completed";
												} 
												if($UserModulesResult['passed']=='n') {
													$pass = 2;
													$active = 1;
													echo "Active";
												}
											} else {
												if($active == 0) {
													$active = 1;
												}
												echo "Active";
											}
										} else {
											echo $userCourseResult['status'];
										}
										
									?>
									<form method="post" style="width:145px">
										<input type="hidden" name="course_id" value="<?php echo $userCourseResult['course_id']; ?>" />
										<input type="hidden" name="update_type" value="course" />
										<input type="hidden" name="time" value="<?php echo $courseResult['ctime']; ?>" />
										<input type="hidden" name="time_type" value="<?php echo $courseResult['course_time_type']; ?>" />
										<select name="status_type">	
											<option value="">Change Status</option>
											<option value="Active">Active</option>
											<option value="Inactive">Inactive</option>
										</select>
										<input type="submit" value="Go" style="width:25px" />
									</form>
									</td>
									<?php if($userCourseResult['status']=='Active') { ?>
										<td><?php echo $userCourseResult['end_date']; ?></td>
										<td><?php if($pass == 1) { echo "<span style='color:green'>Pass</span>"; } if($pass == 2) { echo "<span style='color:red'>Fail</span>"; } ?></td>
										<td>
											<?php 
												if($pass==1) { 
													echo '0'; 
												} elseif($pass == 2) {
													echo (5-$UserModulesResult['attempts']).' - ';
													if($UserModulesResult['attempts']<=5) {
														//$quizlink = get_permalink(842).'?course_id='.$userCourseResult['course_id'].'&module_id='.$CourseModulesResult['module_id'];
														//$quizlink = get_permalink(842).'?course_id='.$userCourseResult['course_id'].'&module_id='.$CourseModulesResult['module_id'];
														if($quiz == 0) {
															//echo "<a href='".$quizlink."'>Take Quize</a>";
															$quiz = 1;
														}	
													}
												} elseif($active == 1){
													//$quizlink = get_permalink(842).'?course_id='.$userCourseResult['course_id'].'&module_id='.$CourseModulesResult['module_id'];
													if($quiz == 0) {
														echo "5 ";
														$quiz = 1;
													}	
												}
											?>
										</td>
									<?php } else { ?>	
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									<?php } ?>
								</tr>
								
					<?php		
								if($active == 1) {
									$active = 2;
								}	
								
								$moduleCount++;
							}
						} else {
							$UserSetsQry = mysql_query("SELECT * FROM user_courses_sets WHERE course_id=".$userCourseResult['course_id']." AND user_id=".$_SESSION['user_id']);
 									$userSets = array();
									$SetModulesIds = array();
									$setModules = array();
									while($UserSetsResult = mysql_fetch_array($UserSetsQry)) {
										$userSets[$UserSetsResult['set_id']] = $UserSetsResult;
										$SetModulesQry = mysql_query("SELECT m.module_id,ms.set_id FROM module m LEFT JOIN sets_modules ms ON m.module_id = ms.module_id WHERE ms.set_id=".$UserSetsResult['set_id']." ORDER BY m.tbl_order");
										while($SetModulesResult = mysql_fetch_array($SetModulesQry)) {
											$SetModulesIds[] = $SetModulesResult['module_id'];
											$setModules[$SetModulesResult['module_id']] = $SetModulesResult;
										}
									}
									/*echo "<pre>";
									print_r($userSets);
									print_r($setModules);
									echo "</pre>";*/
							$CourseModulesQry = mysql_query("SELECT m.* FROM module m LEFT JOIN module_course mc ON m.module_id = mc.module_id WHERE mc.course_id=".$userCourseResult['course_id']." ORDER BY m.tbl_order");
							$moduleCount = 1;
							$active = 0;
							$stop = 0;
							$quiz =0;
							while($CourseModulesResult = mysql_fetch_array($CourseModulesQry)) {
								$pass=0;
								$Inactive = 0;
								$setId = $setModules[$CourseModulesResult['module_id']]['set_id'];
					?>
								<tr>
									<td><?php echo $CourseModulesResult['module_name']; ?></td>
									<td><?php echo $userSets[$setId]['payment_method']; ?></td>
									<td>
									<?php
										
										$UserModulesQry = mysql_query("SELECT * FROM user_register_modules WHERE module_id=".$CourseModulesResult['module_id']." AND user_id=".$_SESSION['user_id']." AND course_id=".$userCourseResult['course_id']);
										if(mysql_num_rows($UserModulesQry) >0) {
											$UserModulesResult = mysql_fetch_array($UserModulesQry);
											if($UserModulesResult['passed']=='y') {
												$pass = 1;
												echo "Completed";
											} 
											if($UserModulesResult['passed']=='n') {
												$pass = 2;
												$active = 1;
												echo "Active";
											}
											
											$attempts = $UserModulesResult['attempts'];
										} else {
											if($stop == 0) {												
												$setStatus = @mysql_result(mysql_query("SELECT `status` FROM `user_courses_sets` WHERE set_id=".$setId." AND `course_id`=".$userCourseResult['course_id']." AND `user_id`=".$_SESSION['user_id']),0);
												if($setStatus == 'Inactive') {
													echo "Inactive";
													$Inactive = 1;
												}elseif(in_array($CourseModulesResult['module_id'],$SetModulesIds) && $userSets[$setId]['paid_amount'] >0) {
													$active = 1;
													echo "Active";
												} else {
													$setlink = $url.'&pageAction=addset&course_id='.$userCourseResult['course_id'].'&set_id='.$setId;
												?>	
													<a onclick="addSet('<?php echo $setlink; ?>')">Add</a>
												<?php	
													$stop = 1;
												}
											}	
										}
										if($stop == 0) {
											$setInfoQry = mysql_query("SELECT * FROM course_sets WHERE  id=".$setId);
											$setInfoResult = mysql_fetch_array($setInfoQry);
									?>
											<form method="post" style="width:145px">
												<input type="hidden" name="set_id" value="<?php echo $setId; ?>" />
												<input type="hidden" name="update_type" value="set" />
												<input type="hidden" name="time" value="<?php echo $setInfoResult['time']; ?>" />
												<input type="hidden" name="time_type" value="<?php echo $setInfoResult['time_type']; ?>" />
												<select name="status_type">	
													<option value="">Change Status</option>
													<option value="Active">Active</option>
													<option value="Inactive">Inactive</option>
												</select>
												<input type="submit" value="Go" style="width:25px" />
											</form>
									<?php } ?>
									</td>
									<td><?php if($stop == 0 && $Inactive == 0) { echo $userSets[$setId]['end_date']; } ?></td>
									<td><?php if($pass == 1  && $Inactive == 0) { echo "<span style='color:green'>Pass</span>"; } if($pass == 2  && $Inactive == 0) { echo "<span style='color:red'>Fail</span>"; } ?></td>
									<td>
										<?php 
											if($pass==1) { 
												echo '0'; 
											} elseif($pass == 2 && $Inactive == 0) {
												echo (5-$attempts).' - ';
												if($setModules[$CourseModulesResult['module_id']]['attempts']<=5) {
													//$quizlink = get_permalink(842).'?course_id='.$userCourseResult['course_id'].'&module_id='.$CourseModulesResult['module_id'];
													if($quiz == 0) {
														//echo "<a href='".$quizlink."'>Take Quize</a>";
														$quiz = 1;
													}	
												}
											} elseif($active == 1 && $Inactive == 0){
												$quizlink = get_permalink(842).'?course_id='.$userCourseResult['course_id'].'&module_id='.$CourseModulesResult['module_id'];
												if($quiz == 0) {
													echo "5 ";
													$quiz = 1;
												}	
											}
										?>
									</td>
								</tr>
					<?php		
								if($active == 1) {
									$active = 2;
								}
								
								$moduleCount++;
							}
						}			
					?>
						</table>
						</div>
					<?php		
						
						
						$countCourses++;
					?>	
					</p>
		</td>
	   </tr>
	   
	   
	 <?php } } ?>
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
<?php } ?>
