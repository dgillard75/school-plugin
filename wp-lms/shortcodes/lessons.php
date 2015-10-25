<?php

//require_once(WPLMS_PLUGIN_PATH.   "class/LMS_DBFunctions.class.php");
//include_once(WPLMS_PLUGIN_PATH.   'class/LMS_School.class.php');

$lessons_page_url = get_permalink(get_the_ID());
$lessons_page = new LMS_FrontEndLessonPage($_GET['id'],get_current_user_id());


$page_pre_reqs = $lessons_page->check_prereqs();
$shortcode_attr = array();

if(!$page_pre_reqs['errors']){
    //$lessons_page = new LMS_FrontEndLessonPage($_GET['id'],get_current_user_id());
    $url = "http://householdstafftraining.com/pdf-courses/";
    $shortcode_attr['embed'] = "[embeddoc url=\"".$url.$lessons_page->get_lesson()[LESSONS_TBL_FILENAME]."\" viewer=\"google\"]";

    if($lessons_page->request_to_mark_unit_as_completed()){
        $lessons_page->student_completed_unit();
    }
}

?>


<style>
    <?php include(WPLMS_PLUGIN_PATH."css/wplms_frontend.css"); ?>
</style>

<?php if(!$page_pre_reqs['errors']) : ?>
    <?php echo do_shortcode($shortcode_attr['embed']); ?>
    <div class="wpcw_fe_progress_box_wrap" id="wpcw_fe_unit_complete_3310">
        <?php if($lessons_page->is_unit_completed()) : ?>
        <div class="wpcw_fe_progress_box wpcw_fe_progress_box_complete">Unit has been completed.</div>
        <?else : ?>
        <div class="wpcw_fe_progress_box wpcw_fe_progress_box_pending ">
            <div class="wp_lms_fe_progress_box_mark">
                    <a href="<?php echo $lessons_page_url."?".$lessons_page->get_completion_unit_request_query_args() ?>" class="lms_btn lms_btn_navigation ">Mark as Completed</a>
            </div>
            Have you completed this unit? Then mark this unit as completed.
        </div>
        <?endif;?>
	</div>
    <div class="wpcw_fe_progress_box_wrap">
        <div class="wpcw_fe_navigation_box">
            <?php if($lessons_page->isPrev()) : ?>
                <a href="<?php echo $lessons_page_url."?id=".$lessons_page->get_previous_lesson() ?>" class="fe_btn fe_btn_navigation">« Previous Unit</a>
            <?php endif; ?>
            <?php if($lessons_page->isNext()) : ?>
                <a href="<?php echo $lessons_page_url."?id=".$lessons_page->get_next_lesson()?>" class="fe_btn fe_btn_navigation ">Next Unit »</a>
            <?php endif; ?>
        </div>
    </div>
<?php else : ?>
    <div class="wpcw_fe_progress_box_wrap"><div class="wpcw_fe_progress_box wpcw_fe_progress_box_error"><?php echo $page_pre_reqs['error_message'] ?></div></div>
<?php endif; ?>