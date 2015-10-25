<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 7/17/15
 * Time: 9:52 AM
 */


define("LMS_FRONTEND_LESSONS_PAGE_ACTION_VAR",  "a");
define("LMS_FRONTEND_LESSONS_PAGE_ID_VAR",      "id");



class LMS_FrontEndLessonPage {

    //lessonObj;

    protected $user_id;
    protected $lesson_data;
    protected $prev_lesson;
    protected $next_lesson;
    protected $user_progress;

    public function LMS_FrontEndLessonPage($lesson_id,$user_id){
        $this->user_id = $user_id;
        $this->init($lesson_id);

    }

    protected function init($lesson_id){
        $this->lesson_data = LMS_DBFunctions::retrieve_lesson($lesson_id);

        $this->user_progress = LMS_School::get_user_progress_given_unitId($lesson_id, $this->user_id);

        $details = $this->user_progress->getNextAndPreviousUnit($lesson_id);

        $this->prev_lesson = $details["prev"];
        $this->next_lesson = $details["next"];
    }

    /**
     * Reload the class and get updates from database
     */
    protected function reload(){
        $lesson_id = $this->lesson_data[LESSONS_TBL_ID];
        $this->init($lesson_id);
    }


    function is_unit_completed(){
        return $this->user_progress->isUnitCompleted($this->lesson_data[LESSONS_TBL_ID]);
    }

    static function valid_parameters(){
        if(isset($_GET['id']) && !empty($_GET['id'])) {
            return true;
        }
        return false;
    }


    static function request_to_mark_unit_as_completed()
    {
        if (isset($_GET[LMS_FRONTEND_LESSONS_PAGE_ACTION_VAR]) &&
            !empty($_GET[LMS_FRONTEND_LESSONS_PAGE_ACTION_VAR]) &&
            $_GET[LMS_FRONTEND_LESSONS_PAGE_ACTION_VAR]=="1") {
            return true;
        }
        return false;
    }

    function student_completed_unit(){
        $rc = LMS_School::mark_unit_completed($this->user_id,$this->lesson_data[LESSONS_TBL_ID]);
        $this->reload();
        return $rc;
    }


    function get_completion_unit_request_query_args(){
        $query_args = LMS_FRONTEND_LESSONS_PAGE_ID_VAR."=".$this->lesson_data[LESSONS_TBL_ID]."&".LMS_FRONTEND_LESSONS_PAGE_ACTION_VAR."=1";
        return $query_args;
    }

    function check_prereqs()
    {
        $prereqs = array();
        if ( !is_user_logged_in() ) {
            //Check if user is logged in
            $prereqs['error_message'] = "You cannot view this unit as you are not logged in.";
            $prereqs['errors'] = true;
        }elseif( !self::valid_parameters() ){
            //Check Get Parameters
            $prereqs['error_message'] = "Sorry but this unit is not accessible at this time";
            $prereqs['errors'] = true;
        }elseif(!(LMS_School::is_authorized(get_current_user_id(),$_GET['id'],IS_AUTH_TYPE_UNIT))){
            // Is User Authorized to view Lesson
            $prereqs['error_message'] = "Sorry, but you're not allowed to access this course.";
            $prereqs['errors'] = true;
        }else if(!$this->user_progress->canUserAccessUnit($this->lesson_data[LESSONS_TBL_ID])){
            // Is User Authorized to view Lesson
            $prereqs['error_message'] = "Sorry, but you're need to complete the previous lesson, please mark as completed.";
            $prereqs['errors'] = true;
        }else{
            $prereqs['errors'] = false;
        }

        return $prereqs;
    }

    public function isPrev(){
        if(!empty($this->prev_lesson))
            return true;
        return false;
    }

    public function isNext(){
        if(!empty($this->next_lesson))
            return true;
        return false;
    }

    public function get_previous_lesson(){
        return $this->prev_lesson;
    }

    public function get_next_lesson(){
        return $this->next_lesson;
    }

    public function get_lesson(){
        return $this->lesson_data;
    }

    public static function showError($error){


    }

    public function print_r(){
        LMS_Log::print_r($this->prev_lesson,__FUNCTION__,  "Previous Lesson");
        LMS_Log::print_r($this->lesson_data,__FUNCTION__,"Current Lesson");
        LMS_Log::print_r($this->next_lesson,__FUNCTION__,"Next Lesson");
    }
}