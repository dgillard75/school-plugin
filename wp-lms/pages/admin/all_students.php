<?php
/**
 **/



ini_set('display_errors', true);
error_reporting(E_ALL);


//init for args get all students.
$args = array();
//Default args for url
$args_url["order"] = "desc";

if (LMS_PageFunctions::hasFormBeenSubmitted()) {
    $searchterm = $_POST['searchterm'];
    $args['search'] = "*".$searchterm."*";
}else {
    if (isset($_GET["orderby"])) {
        $args["order"] = $_GET["orderby"];
    }

    if (isset($_GET["order"])) {
        $args["order"] = $_GET["order"];
        if ($_GET["order"] == "asc")
            $args_url["order"] = "desc";
        else
            $args_url["order"] = "asc";
    }
}

/** @var  $allstudents  - Get all the registered students*/
$allstudents = LMS_School::get_all_students($args);
?>

<!-- Student PAGE Title With SearchBox -->
<div class="wrap">
    <div id="icon-edit-pages" class="icon32"></div>
    <h2>Students<a class="add-new-h2" href="<?php echo $addstudenturl ?>">Add New</a></h2>
    <p class="search-box">
        <label for="post-search-input" class="screen-reader-text">Search Student:</label>
    <form action="<?php LMS_PageFunctions::getAdminUrlPage("",is_ssl())?>" method="POST">
        <input type="search" value="" name="searchterm" id="postsearchinput">
        <input type="submit" value="Searchstudent" class="button" id="searchsubmit" name="searchsubmit"></form>
    ( Enter Student's First Name / Last Name / Username / Email to Search )
    </p>
</div>

<table id="wplms_tbl_student_list" class="widefat wpcw_tbl" class="widefat">
    <thead>
    <tr>
        <th id="user_id" scope="col" class="sortable"><a href="<?php echo WPLMS_STUDENTS_URL."&order=".$args_url["order"]."&orderby=ID"?> "><span>User Id</span><span class="sorting-indicator"></span></a></th>
        <th id="username" scope="col" class="sorted <?php echo $args_url["order"] ?>"><a href="<?php echo WPLMS_STUDENTS_URL."&order=".$args_url["order"]."&orderby=login"?> "><span>Username</span><span class="sorting-indicator"></span></a></th>
        <th id="details" scope="col" >Details</th>
        <th id="course_progress" scope="col" >Course Progress</th>
        <th id="actions" scope="col" >Actions</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th id="user_id" scope="col" class="sortable"><a href="<?php echo WPLMS_STUDENTS_URL."&order=".$args_url["order"]."&orderby=ID"?> "><span>User Id</span><span class="sorting-indicator"></span></a></th>
        <th id="username" scope="col" class="sorted <?php echo $args_url["order"] ?>"><a href="<?php echo WPLMS_STUDENTS_URL."&order=".$args_url["order"]."&orderby=login"?> "><span>Username</span><span class="sorting-indicator"></span></a></th>
        <th id="details" scope="col" >Details</th>
        <th id="course_progress" scope="col" >Course Progress</th>
        <th id="actions" scope="col" >Actions</th>
    </tr>
    </tfoot>
    <tbody>
    <?php if(count($allstudents) > 0) : ?>
    <?php foreach ( $allstudents as $student ): $studentInfo = $student->getUserInformation(); $courselist = $student->getCourseList(); ?>
        <tr class="alternate">
            <td class="user_id"><?php echo  $studentInfo["user_id"]; ?></td>
            <td class="username"><?php echo $studentInfo["user_login"]; ?></td>
            <td class="details">
                <ul>
                    <li><?php echo $studentInfo["first_name"]."  ".$studentInfo["last_name"] ?></li>
                    <li><?php echo $studentInfo["user_email"]; ?></li>
                </ul>
            <td class="course_progress">
                <?php if(empty($courselist)) : ?>
                <span class="wplms_progress_wrap">
		            <span class="wplms_progress_percent">Not signed up for any courses.</span>
                </span>
                <?php else : ?>
                <?php foreach ( $courselist as $c ) : $courseInfo = $c->getCourse();  $progress = new LMS_UserProgress($courseInfo[STUDENT_COURSE_LIST_CID],$courseInfo[STUDENT_COURSE_LIST_UID]); ?>
                <span class="wplms_progress_wrap">
		            <span class="wplms_progress">
			            <span class="wplms_progress_bar" style="width: <?php echo $progress->getCoursePercentageCompleted()."%" ?>"></span>
		            </span>
		            <span class="wplms_progress_percent"><?php echo $progress->getCoursePercentageCompleted()."%" ?></span>
		            <span class="wplms_progress_bar_title"><?php echo $courseInfo[STUDENT_COURSE_LIST_CNAME] ?></span>
                </span>
                <?php endforeach; ?>
                <?php endif; ?>
            </td>
            <td class="actions">
                <ul>
                    <li><a href="<?php echo LMS_PageFunctions::wp_admin_url()."?page=".WPLMS_ADMIN_VIEW_DETAILS_PAGE_ID  ?>" class="button-primary">View Detailed Progress</a></li>
                    <li><a href="<?php echo LMS_PageFunctions::wp_admin_url()."?page=".WPLMS_ADMIN_COURSE_ACCESS_PAGE_ID."&user_id=".$studentInfo['user_id'] ?>" class="button-secondary">Update Course Access</a></li>
                </ul>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php else : ?>
    <tr class="alternate"><td class="colspanchange" colspan="5">No users found.</td> </tr>
    <?php endif; ?>
    </tbody>
</table>