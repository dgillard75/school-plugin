<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 8/12/15
 * Time: 1:29 PM
 */

include_once(WPLMS_PLUGIN_PATH."class/htmlform/LMS_CourseHTMLForm.class.php");

$args = LMS_PageFunctions::get_args_for_search_and_sort();

$quizzes = LMS_DBFunctions::get_all_quizzes($args);

//LMS_Log::print_r($args);
//LMS_Log::print_r($quizzes);
?>

<h2>Quiz &amp; Survey Summary</h2>

<form id="wpcw_quizzes_search_box" method="get" action="<?php echo WPLMS_QSUMMARY_URL ?>">
    <p class="search-box">
        <label class="screen-reader-text" for="wpcw_quizzes_search_input">Search Quizzes</label>
        <input id="wpcw_quizzes_search_input" type="text" value="" name="search"/>
        <input class="button" type="submit" value="Search Quizzes"/>

        <input type="hidden" name="page" value="<?php echo WPLMS_ADMIN_QSUMMARY_PAGE_ID ?>" />
    </p>
</form>
<br/><br/>
<div class="tablenav wpcw_tbl_paging">
    <div class="wpbs_paging tablenav-pages"><span class="displaying-num">Displaying 1 &ndash; 50 of <?php echo count($quizzes) ?></span><a href="https://testenv.com/school/wp-admin/admin.php?page=WPCW_showPage_QuizSummary&pagenum=1" class="page-numbers current" data-pagenum="1">1</a><a href="https://testenv.com/school/wp-admin/admin.php?page=WPCW_showPage_QuizSummary&pagenum=2" class="page-numbers " data-pagenum="2">2</a><a href="https://testenv.com/school/wp-admin/admin.php?page=WPCW_showPage_QuizSummary&pagenum=3" class="page-numbers " data-pagenum="3">3</a><a href="https://testenv.com/school/wp-admin/admin.php?page=WPCW_showPage_QuizSummary&pagenum=2" class="next page-numbers" data-pagenum="2">&raquo;</a></div>
</div>
<table id="wpcw_tbl_quiz_summary" class="widefat wpcw_tbl" class="widefat">
    <thead>
    <tr>
        <th id="quiz_id" scope="col" class="sorted <?php echo $args[QUERY_ORDER_NEXT_REQ]?>"><a href="<?php echo WPLMS_QSUMMARY_URL."&pagenum=&".QUERY_ORDER_VAR."=".$args[QUERY_ORDER_NEXT_REQ]."&".QUERY_ORDER_BY_VAR."=quiz_id"?>"><span>ID</span><span class="sorting-indicator"></span></a></th>
        <th id="quiz_title" scope="col" class="sortable"><a href="<?php echo WPLMS_QSUMMARY_URL."&pagenum=&".QUERY_ORDER_VAR."=".$args[QUERY_ORDER_NEXT_REQ]."&".QUERY_ORDER_BY_VAR."=quiz_title"?>"><span>Quiz Title</span><span class="sorting-indicator"></span></a></th>
        <th id="associated_unit" scope="col" >Associated Unit</th>
        <th id="total_questions" scope="col" >Questions</th>
        <th id="actions" scope="col" >Actions</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th id="quiz_id" scope="col" class="sorted <?php echo $args[QUERY_ORDER_NEXT_REQ]?>"><a href="<?php echo WPLMS_QSUMMARY_URL."&pagenum=&".QUERY_ORDER_VAR."=".$args[QUERY_ORDER_NEXT_REQ]."&".QUERY_ORDER_BY_VAR."=quiz_id"?>"><span>ID</span><span class="sorting-indicator"></span></a></th>
        <th id="quiz_title" scope="col" class="sortable"><a href="<?php echo WPLMS_QSUMMARY_URL."&pagenum=&".QUERY_ORDER_VAR."=".$args[QUERY_ORDER_NEXT_REQ]."&".QUERY_ORDER_BY_VAR."=quiz_title"?>"><span>Quiz Title</span><span class="sorting-indicator"></span></a></th>
        <th id="associated_unit" scope="col" >Associated Unit</th>
        <th id="total_questions" scope="col" >Questions</th>
        <th id="actions" scope="col" >Actions</th>
    </tr>
    </tfoot>
    <tbody>
    <?php foreach($quizzes as $quiz) :  $course = LMS_DBFunctions::get_course_info_by_module_id($quiz[QUIZ_TABLE_MID]); ?>
    <tr class="alternate">
        <td class="quiz_id"><?php echo $quiz[QUIZ_TABLE_ID] ?></td>
        <td class="quiz_title"><b><a href="<?php echo WPLMS_QMODIFY_EDIT_URL."&quizID=".$quiz[QUIZ_TABLE_ID]?>"><?php echo $quiz[QUIZ_TABLE_TITLE] ?></a></b></td>
        <td class="associated_unit">
            <span class="associated_unit_unit"><b>Unit</b>: <a href="http://testenv.com/school/module-5/cgh-m5-quiz/" target="_blank" title="View  '<?php echo $quiz['module_name'] ?>'..."><?php echo $quiz['module_name'] ?> </a></span>
            <span class="associated_unit_course"><b>Course:</b> <a href="<?php echo WPLMS_EDIT_COURSE_URL."&".CRS_FORM_COURSE_ID."=".$course[COURSES_TBL_COURSE_ID] ?>" title="Edit  '<?php echo $course[COURSES_TBL_CNAME] ?>'..."><?php echo $course[COURSES_TBL_CNAME] ?></a></span>
        </td>
        <td class="total_questions wpcw_center"><?php echo LMS_DBFunctions::get_total_number_of_questions($quiz[QUIZ_TABLE_ID]); ?></td>
        <td class="actions">
            <ul class="wpcw_action_link_list">
                <li><a href="<?php echo WPLMS_QMODIFY_EDIT_URL."&quizID=".$quiz[QUIZ_TABLE_ID]?>" class="button-primary">Edit</a></li>
                <li><a href="https://testenv.com/school/wp-admin/admin.php?page=WPCW_showPage_QuizSummary&pagenum=&action=delete&quiz_id=115" class="button-secondary wpcw_action_link_delete_quiz wpcw_action_link_delete" rel="Are you sure you wish to delete this quiz?">Delete</a></li>
            </ul>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
