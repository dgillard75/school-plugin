<?php
require('dbfunctions.php');

$message = '';
if(isset($_GET['courseId']) && $_GET['courseId'] != '') {
	deleteStudent($_GET['delId']);
	$message="del";
}else{
	$message="noid";
}


$term_id = @mysql_result(mysql_query("SELECT `term_id` FROM course WHERE id =".$_GET['delId']),0);

$query="DELETE FROM course WHERE id =".$_GET['delId'];
$re1=mysql_query($query);
wp_delete_term($term_id, 'course');

mysql_query("DELETE FROM `package_course` WHERE `course_id` = ".$_GET['delId']);
mysql_query("DELETE FROM `module_course` WHERE `course_id` = ".$_GET['delId']);
mysql_query("DELETE FROM `course_sets` WHERE `course_id` = ".$_GET['delId']);


$location = $_SERVER['HTTP_REFERER'].'&msg='.$message;
wp_redirect($location);
?>
