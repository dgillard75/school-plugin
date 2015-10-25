<?php
$permissions = LMS_School::check_student_permissions();
?>

<br>
<style>
    <?php include(WPLMS_PLUGIN_PATH."css/wplms_frontend.css"); ?>
    <?php include(WPLMS_PLUGIN_PATH."css/mycourses.css"); ?>
</style>
<script>
    <?php include(WPLMS_PLUGIN_PATH."js/mycourse.js"); ?>
</script>

<?php
    if($permissions == LMS_SCHOOL_IS_AUTHORIZED) :
    $student_courses = new LMS_StudentCourses(get_current_user_id());
    $list_of_courses = $student_courses->getCourseList();
?>

<div class="accordion vertical">
<ul>
    <?php for($i=0; $i < count($list_of_courses); $i++) : $course = $list_of_courses[$i]->getCourse(); ?>
    <li>
        <input type="checkbox" id="<?php echo "checkbox".$i ?>" name="checkbox-accordion" />
        <label for="<?php echo "checkbox".$i ?>"><?php echo $course['course_title'] ?></label>
        <?php
        $users_progress = new LMS_UserProgress($course[COURSES_TBL_COURSE_ID],get_current_user_id());
        $modules = $list_of_courses[$i]->getListOfModules();
        for($j=0; $j < count($modules); $j++) :
        $moduleNumber = $j + 1;
        $idName = "wpcw_fe_module_group_" . $moduleNumber;
        ?>
        <div class="content">
            <!-- Module and Unit Information -->
            <table id="wpcw_fe_course" class="wpcw_fe_table" cellspacing="0" cellborder="0">
                <tr class="wpcw_fe_module " id="<?php echo $idName?>">
                    <td>Module <?php echo $j+1 ?></td>
                    <td colspan="2"><?php echo $modules[$j][MODULES_TBL_MODULE_NAME]?></td>
                </tr>
                <?php
                $lessons = $modules[$j]['lessons'];
                for($k=0; $k < count($lessons); $k++) :
                $trClassName = "wpcw_fe_unit wpcw_fe_unit_pending wpcw_fe_module_group_".$moduleNumber;
                ?>
                <tr class="wpcw_fe_unit <?php echo $trClassName ?>" >
                    <td>Unit <?php echo $k+1 ?></td>
                    <td class="wpcw_fe_unit">
                        <?php LMS_Log::print_r($lessons[$k][LESSONS_TBL_ID]); if($users_progress->canUserAccessUnit($lessons[$k][LESSONS_TBL_ID])) : ?>
                        <a href="<?php echo LMS_School::get_lesson_url()."?id=".$lessons[$k][LESSONS_TBL_ID]?>"><?php echo $lessons[$k]['title'] ?></a>
                        <?php else : ?>
                        <?php echo $lessons[$k]['title'] ?>
                        <?php endif; ?>
                    </td>
                    <?php if($users_progress->isUnitCompleted($lessons[$k][LESSONS_TBL_ID])) : ?>
                    <td class="wpcw_fe_unit_progress wpcw_fe_unit_progress_complete"><span>&nbsp;</span></td>
                    <?php else : ?>
                     <td class="wpcw_fe_unit_progress wpcw_fe_unit_progress_incomplete"><span>&nbsp;</span></td>
                    <?php endif;  ?>
                </tr>
                <?php endfor; ?>
            </table>
        </div>
        <?php endfor; ?>
    </li>
    <?php endfor; ?>
</ul>
</div>

<?php else : ?>
    <div class="wpcw_fe_progress_box_wrap"><div class="wpcw_fe_progress_box wpcw_fe_progress_box_error"><?php echo LMS_School::$message[$permissions]?></div></div>
<?php endif; ?>


