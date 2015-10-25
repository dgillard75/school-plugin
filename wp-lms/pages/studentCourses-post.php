<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/11/15
 * Time: 3:16 PM
 */


if(!empty($_POST['status_type'])){



}


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
