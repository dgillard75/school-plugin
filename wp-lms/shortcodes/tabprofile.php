


<?php
ini_set('display_errors', true);
error_reporting(E_ALL);


include_once(WPLMS_PLUGIN_PATH.     "LMS_PageFunctions.php");
include_once(WPLMS_PLUGIN_PATH.     "StudentAccount.php");
include_once(WPLMS_PLUGIN_PATH.     "AllStudentCourses.php");

//Testing
$user_id="303";
$_SESSION['user_id']=$user_id;
$studentAcct = new StudentAccount($user_id);
$studentInfo = $studentAcct->getInfo();
$allstudentcourses = $studentAcct->getAllCourses();

LMS_SchoolDB::debug($allstudentcourses);

?>

<style>
    <?php include(WPLMS_PLUGIN_PATH."css/simpletabs.css"); ?>
</style>

<div id="main-col">
	<div id="content">
		<article class="whitearea">
			<div class="entry-container fix">
				<div class="entry entry-content fix">
                    <div class="simpleTabs">
	        	        <ul class="simpleTabsNavigation">
		                    <li><a href="#">Account Information</a></li>
		                    <li><a href="#">Courses/Packages</a></li>
        		        </ul>
				        <!----------First tab profile------------------>
		                <div class="simpleTabsContent">
				        <?php
				//$selectrow = mysql_query("select * from `tbl_users` where id='".$userid."'");
				//$row = mysql_fetch_array($selectrow);

				?>


                        </div>
<!----------end First tab profile------------------>
<!----------Second tab Courses------------------>
<div class="simpleTabsContent" >
    <?php
    $countCourses = 1;

    $numOfCourses = $studentAcct->getNumberOfCourses();

    if($numOfCourses <= 0) :
        echo "<div style='padding-left:130px;'>You did not signed up for any course/package yet.
							Use links below to find your course:<br/><br/>

								<a href='".get_permalink('472')."'>All Courses</a><br/>
								<a href='".get_permalink('692')."'>2-course packages</a><br/>
								<a href='".get_permalink('698')."'>3-course packages</a><br/>
								<a href='".get_permalink('700')."'>4-course packages</a><br/></div>";
    else :
        /***************************     USERS COURSES      **************************/
        foreach($allstudentcourses->getCourses() as $scourseObj):
            $student_course = $scourseObj->getCourse();
            $student_modules = $scourseObj->getListOfModules();


//    while($userCourseResult = mysql_fetch_array($userCourseQry)) {
            //$courseQry = mysql_query("SELECT * FROM course WHERE id='".$userCourseResult['course_id']."'");
            //$courseResult = mysql_fetch_array($courseQry);
            //if(time()>strtotime($userCourseResult['end_date']) && $userCourseResult['status']=='Active') {
               // mysql_query("UPDATE user_courses SET status = 'Expired' WHERE user_id='".$_SESSION['user_id']."' && course_id =".$userCourseResult['course_id']);
            //}
            //$courseQry = mysql_query("SELECT * FROM course WHERE id='".$userCourseResult['course_id']."'");
            //$courseResult = mysql_fetch_array($courseQry);
            ?>
            <p>
            <?php if($student_course['status']=='Active'): ?>
                <div>
                    <b>
                        <?php echo $countCourses.". ";?>
                        <a onclick="expendme('expendModules_<?php echo $student_course['course_id']; ?>')"><?php echo $student_course['course_name']; ?></a>
                    </b>
                </div>
                <div class="expendModules_<?php echo $student_course['course_id']; ?>" style="display:none;">

                    <table class="modulesContainer">
                        <thead>
                        <th>Modules</th>
                        <th>Status</th>
                        <th>Expiration Date</th>
                        <th>Topics</th>
                        <th>Quiz Status</th>
                        <th>Attempts Left</th>
                        </thead>
                        <?php
                        if($student_course['signup_type']==1) :
                            //$CourseModulesQry = mysql_query("SELECT m.* FROM module m LEFT JOIN module_course mc ON m.module_id = mc.module_id WHERE mc.course_id=".$userCourseResult['course_id']." ORDER BY m.tbl_order");
                            $moduleCount = 1;
                            $active = 0;
                            $quiz = 0;
                            $expire = 0;
                            foreach($student_modules as $module):
                                $pass=0;
                           // while($CourseModulesResult = mysql_fetch_array($CourseModulesQry)) {
                         ?>
                                <tr>
                                    <td><?php echo $module['module_name']; ?></td>
                                    <td>
                                        <?php  echo "Active";
                                        /**
                                           $UserModulesQry = mysql_query("SELECT * FROM user_register_modules WHERE module_id=".$CourseModulesResult['module_id']." AND course_id=".$userCourseResult['course_id']." AND user_id=".$_SESSION['user_id']);
                                        if(mysql_num_rows($UserModulesQry) >0) {
                                            $UserModulesResult = mysql_fetch_array($UserModulesQry);


                                            if($UserModulesResult['passed']=='y') {
                                                $pass = 1;
                                                echo "Paid - Completed";
                                            }

                                            if($UserModulesResult['passed']=='n') {
                                                $pass = 2;
                                                $active = 1;
                                                echo "Paid - Active";
                                            }
                                        } else {
                                            if($active == 0) {
                                                $active = 1;
                                            }
                                            echo "Paid - Active";
                                        }
                                         * **/
                                        ?>
                                    </td>
                                    <td><?php echo $student_course['end_date']; ?></td>
                                    <td><?php
                                        if(($pass==1 || $active == 1) && $quiz == 0 ) { echo "<a href='".get_permalink($student_course['pageid'])."?course_id=".$student_course['course_id']."'>View Topics</a>"; } else { echo "View Topics"; }
                                        ?>
                                    </td>
                                    <td><?php
                                        if($pass == 1) { echo "<span style='color:green'>Pass</span>"; } if($pass == 2) { echo "<span style='color:red'>Fail</span>"; }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            if($pass==1) {
                                                echo '0';
                                        /*lseif($pass == 2) {
                                            echo (5-$UserModulesResult['attempts']).' - ';
                                            if($UserModulesResult['attempts']<5) {
                                                $quizlink = get_permalink(842).'?course_id='.$userCourseResult['course_id'].'&module_id='.$CourseModulesResult['module_id'];
                                                $quizlink = get_permalink(842).'?course_id='.$userCourseResult['course_id'].'&module_id='.$CourseModulesResult['module_id'];
                                                if($quiz == 0) {
                                                    echo "<a href='".$quizlink."'>Take Quiz</a>";
                                                    $quiz = 1;
                                                }
                                            }*/
                                            }elseif($active == 1){
                                                $quizlink = get_permalink(842).'?course_id='.$student_course['course_id'].'&module_id='.$module['module_id'];
                                                if($quiz == 0) {
                                                    echo "5 - <a href='".$quizlink."'>Take Quiz</a>";
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

                                    if($pass == 2 ) {
                                        $quiz = 1;
                                    }


                                    $moduleCount++;
                            endforeach;
                        else :
                            foreach($student_modules as $module):

                            /*echo "<pre>";
                            print_r($userSets);
                            print_r($setModules);
                            echo "</pre>";*/
                            $moduleCount = 1;
                            $active = 0;
                            $stop = 0;
                            $quiz =0;
                            //while($CourseModulesResult = mysql_fetch_array($CourseModulesQry)) {
                                $setId = $module["set_id"];
                                $pass=0;
                                $Inactive = 0;
                                ?>

                                <tr>
                                    <td><?php echo $module['module_name']; ?></td>
                                    <td>
                                    <?php
                                        echo "??????";
                                        /**


                                        $UserModulesQry = mysql_query("SELECT * FROM user_register_modules WHERE module_id=".$CourseModulesResult['module_id']." AND user_id=".$_SESSION['user_id']." AND course_id=".$userCourseResult['course_id']);
                                        if(mysql_num_rows($UserModulesQry) >0) {
                                            $UserModulesResult = mysql_fetch_array($UserModulesQry);
                                            if($UserModulesResult['passed']=='y') {
                                                $pass = 1;
                                                echo "Paid - Completed";
                                            }
                                            if($UserModulesResult['passed']=='n') {
                                                $pass = 2;
                                                $active = 1;
                                                echo "Paid - Active";
                                            }

                                            $attempts = $UserModulesResult['attempts'];
                                        } else {
                                            if($stop == 0) {


                                                $setStatus = @mysql_result(mysql_query("SELECT `status` FROM `user_courses_sets` WHERE set_id=".$setId." AND `course_id`=".$userCourseResult['course_id']." AND `user_id`=".$_SESSION['user_id']),0);
                                                $setpaid_amount = @mysql_result(mysql_query("SELECT `paid_amount` FROM `user_courses_sets` WHERE set_id=".$setId." AND `course_id`=".$userCourseResult['course_id']." AND `user_id`=".$_SESSION['user_id']),0);
                                                //echo 'a '.$setStatus.' b'.$setpaid_amount;
                                                if($setStatus == 'Inactive' && $setpaid_amount>0) {
                                                    echo "Paid / Waiting Approval";
                                                    $Inactive = 1;
                                                }elseif(in_array($CourseModulesResult['module_id'],$SetModulesIds) && $userSets[$setId]['paid_amount'] >0) {
                                                    $active = 1;
                                                    echo "Paid - Active";
                                                } else {
                                                    $setlink = get_permalink(933).'?course_id='.$userCourseResult['course_id'].'&set_id='.$setId;
                                                    echo "<a href='".$setlink."'>Sign Up</a>";
                                                    $stop = 1;
                                                }
                                            }
                                        }**/
                                        ?>
                                    </td>
                                    <td><?php if($stop == 0 && $Inactive == 0) { echo $module['end_date']; } ?></td>

                                    <td><?php if(($pass==1 || $active == 1 )  && $Inactive == 0 && $quiz == 0) { echo "<a href='".get_permalink($module['pageid'])."?course_id=".$student_course['course_id']."'>View Topics</a>"; } else { echo "View Topics"; }  ?></td>
                                    <td><?php if($pass == 1  && $Inactive == 0) { echo "<span style='color:green'>Pass</span>"; } if($pass == 2  && $Inactive == 0) { echo "<span style='color:red'>Fail</span>"; } ?></td>
                                    <td>
                                        <?php
                                        if($pass==1) {
                                            echo '0';
                                        } elseif($pass == 2 && $Inactive == 0) {
                                            echo (5-$attempts).' - ';
                                            if($attempts<5) {
                                                $quizlink = get_permalink(842).'?course_id='.$student_course['course_id'].'&module_id='.$module['module_id'];
                                                if($quiz == 0) {
                                                    echo "<a href='".$quizlink."'>Take Quiz</a>";
                                                    $quiz = 1;
                                                }
                                            }
                                        } elseif($active == 1 && $Inactive == 0){
                                            $quizlink = get_permalink(842).'?course_id='.$student_course['course_id'].'&module_id='.$module['module_id'];
                                            if($quiz == 0) {
                                                echo "5 - <a href='".$quizlink."'>Take Quiz</a>";
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
                                if($pass == 2 ) {
                                    $quiz = 1;
                                }

                                $moduleCount++;
                            endforeach;
                        endif;
                        ?>
                    </table>
                </div>
                <?php elseif($student_course['status']=='Inactive') :?>
                <div>
                    <b>
                        <?php //".get_term_link(intval($courseResult['term_id']), 'course')."
                        echo $numOfCourses.". ";?>
                        <a onclick="expendme('expendModules_<?php echo $student_course['course_id']; ?>')"><?php echo $student_course['course_name']; ?></a> - <span style='color:red'>Inactive</span>
                    </b>
                </div>
                <div class="expendModules_<?php echo $student_course['course_id']; ?>" style="display:none;">
                    <div style='color:red; font-weight:bold'> Currently you don't have an access to study material.<br/>
                        Administrator will enable it as soon as we receive your Money Order
                    </div>
                    <div class="money_order">
                        <p>Make money order payable to:<br/>
                            <b>CA & NY Celebrities Group, Inc.</b>
                        </p>
                        <p>Mail the check to:<br/>
                            <b>CA & NY Celebrities Group, Inc.<br/>
                                9201 Wilshire Blvd. Suite 205<br/>
                                Beverly Hills, CA 90210</b>
                        </p>
                    </div>
                </div>
                <?php elseif($student_course['status']=='Expired') :
                    echo "<b>".$countCourses.". ".$courseResult['cname']."</b> - <span style='color:red'>Expired</span>";
                endif;
                ?>
        </p>
        <?php
        $countCourses++;
        endforeach;
    endif;
    /***************************     USERS COURSES      **************************/
    ?>



</div>
<!----------end Second tab Courses--------------->

</div>
</div><!--entry -->
</div><!-- .entry-container -->
</article><!--post -->
</div><!-- #content -->
</div><!-- #main-col -->

<script type="text/javascript">
    function expendme(clsName) {
        var cls = '.'+clsName;
        jQuery(cls).slideToggle('slow');
    }
</script>