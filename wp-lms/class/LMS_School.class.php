<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 7/15/15
 * Time: 11:03 AM
 */

//include_once(LMS_PLUGIN_PATH    ."class/LMS_DBFunctions.class.php");
//include_once(WPLMS_PLUGIN_PATH  ."class/LMS_StudentCourses.class.php");

define("IS_AUTH_TYPE_COURSE","BY COURSE");
define("IS_AUTH_TYPE_MODULE","BY MODULE");
define("IS_AUTH_TYPE_UNIT","BY UNIT");

define("LMS_SCHOOL_IS_AUTHORIZED","1");
define("LMS_SCHOOL_NOT_LOGGED_IN","2");
define("LMS_SCHOOL_NOT_AUTHORIZED", "3");


class LMS_School {

    public static $message = array(
        LMS_SCHOOL_IS_AUTHORIZED => "Authorized",
        LMS_SCHOOL_NOT_LOGGED_IN => "You cannot view this unit as you are not logged in.",
        LMS_SCHOOL_NOT_AUTHORIZED => "Sorry, but you're not allowed to access this course."
    );

    /**
     * Checks to see if course is valid
     *
     * @param $course_id
     * @return bool
     */
    protected static function is_course_valid($course_id){
        $course = LMS_DBFunctions::retrieve_course($course_id);
        if(empty($course)){
            return false;
        }

        return true;
    }

    /**
     * Checks to see if is module is valid.
     *
     * @param $course_id
     * @param $module_id
     * @return bool
     */
    protected static function is_module_valid($course_id, $module_id)
    {
        $row = LMS_DBFunctions::get_course_module_by_id($module_id);
        if(!empty($row) && $row['course_id']==$course_id){
            return true;
        }
        return false;
    }


    /**
     * Get lesson URL, should be in database settings.
     *
     * @return string
     */
    public static function get_lesson_url()
    {
        // Get Lesson Url from Databas, for now hard code
        $lesson_url =  "https://".$_SERVER["HTTP_HOST"]."/school/lessons";
        return $lesson_url;

    }

    /**
     * Get quiz URL, should be in database settings.
     *
     * @return string
     */
	public static function get_quiz_url()
	{
		// Get Lesson Url from Databas, for now hard code
		$url = "http://localhost/school/quiz";
		return $url;

	}


    public static function get_user_progress_given_unitId($unitId, $userId){
        $unit_info = LMS_DBFunctions::retrieve_lesson($unitId);
        //Need to put a check here in the future

        // Mark as completed on for authorized users enrolled
        $user_progress = new LMS_UserProgress($unit_info[LESSONS_TBL_COURSE_ID],$userId);
        return $user_progress;
    }

    /**
     * Mark Unit as completed.
     *
     * @param $userId
     * @param $unitId
     * @param bool $validation
     * @return bool
     */
    public static function mark_unit_completed($userId, $unitId, $validation=true){


        $unit_info = LMS_DBFunctions::retrieve_lesson($unitId);
        if(!$unit_info){
            return false;
        }

        // Mark as completed on for authorized users enrolled
        $user_progress = new LMS_UserProgress($unit_info[LESSONS_TBL_COURSE_ID],$userId);
        if($validation && !$user_progress->canUserAccessCourse()){
            return false;
        }

        // Mark as completed on for authorized users enrolled
        if($user_progress->isUnitCompleted($unitId)){
            return true;
        }

        // We got here so time to insert progress in database
        $data[USER_PROGRESS_TABLE_USER_ID] = $userId;
        $data[USER_PROGRESS_TABLE_UNIT_ID] = $unitId;
        $data[USER_PROGRESS_TABLE_COMPLETE_DATE] = date('Y-m-d H:i:s');
        $data[USER_PROGRESS_TABLE_STATUS] = "COMPLETED";

        return LMS_DBFunctions::add_user_progress_entry($data);
    }



    /**
     * @param $student_info
     */
    public static function register($student_info){

    }

    /**
     * @param $user_id
     * @param $course_id
     * @param $who_granted
     * @param null $module_id
     * @return int
     */
    public static function enroll($user_id, $course_id, $who_granted,$module_id=NULL)
    {
        //Check to see if user granted access, if so just return.
        //Need to check errors here
        if(LMS_DBFunctions::get_user_course_by_course($user_id,$course_id)!=NULL){
            return 1;
        }

        $data[USER_COURSES_TBL_COURSE_ID] = $course_id;
        $data[USER_COURSES_TBL_USER_ID] = $user_id;
        $data[USER_COURSES_TBL_GRANTOR] = $who_granted;
        $data[USER_COURSES_TBL_STATUS] = 1;

        /**
         * Add Course to User Course table as ACTIVE, get a TRANS_ID
         */
        $rc = LMS_DBFunctions::add_user_course_entry($data);
        if ($rc == false){
            return $rc;
        }

        $totalRowsInserted=1;

        $trans_id = $rc; //Trans Id will be returned upon success
        $modules = LMS_DBFunctions::get_course_modules($course_id, true);
        foreach ($modules as $m) {
            $mdata[USER_MODULES_TBL_TRANS_ID] = $trans_id;
            $mdata[USER_MODULES_TBL_MODULE_ID] = $m->module_id;
            $mdata[USER_MODULES_TBL_GRANTOR] = $who_granted;
            if($module_id==NULL){
                $mdata[USER_MODULES_TBL_STATUS] = USER_COURSES_STATUS_ACTIVE;
            }elseif($module_id == $m->module_id){
                $mdata[USER_MODULES_TBL_STATUS] = USER_COURSES_STATUS_ACTIVE;
            }else{
                $mdata[USER_MODULES_TBL_STATUS] = USER_COURSES_STATUS_INACTIVE;
            }
            /**
             * Add Module to User_Module table as ACTIVE
             */
            LMS_DBFunctions::add_user_module_entry($mdata);
            $totalRowsInserted++;
        }

        return $totalRowsInserted;
    }

    /**
     * DisEnroll student from course. a
     *
     * @param $user_id
     * @param $course_id
     */
    public static function disenroll($user_id, $course_id){

        //Retrieve Transaction ID, and Remove from User Course and User Module
        $user_course = LMS_DBFunctions::get_user_course_by_course($user_id, $course_id);
        if(!$user_course)
            return false;

        LMS_DBFunctions::delete_user_course($user_course[USER_COURSES_TBL_TRANS_ID]);
        LMS_DBFunctions::delete_user_modules($user_course[USER_COURSES_TBL_TRANS_ID]);

        return true;
    }

    /**
     * @param $user_id
     * @param $course_id
     * @param $module_id
     * @param $who_granted
     * @param null $trans_id
     * @return bool
     */
    public static function enroll_by_module($user_id, $course_id, $module_id, $who_granted, $trans_id=NULL)
    {
        //validate course_id
        if (!self::is_course_valid($course_id)) {
            return false;
        }

        //validate
        if (!empty($module_id) && !self::is_module_valid($course_id, $module_id)) {
            return false;
        }

        //Get Transaction ID from User_course table
        if(empty($trans_id)) {
            $user_course = LMS_DBFunctions::get_user_course_by_course($user_id, $course_id);
            $trans_id = $user_course['trans_id'];
        }

        //Grant Access
        $data[USER_MODULES_TBL_TRANS_ID] = $trans_id;
        $data[USER_MODULES_TBL_MODULE_ID] = $module_id;
        $data[USER_MODULES_TBL_GRANTOR] = $who_granted;
        //$data[USER_MODULES_TBL_STATUS] = 1;
        LMS_Log::print_r($data);
        LMS_DBFunctions::add_user_module_entry($data);
        return true;
    }

    /**
     * @param $user_id
     * @param $who_granted
     * @param $course_id
     */
    public static function remove_course($user_id, $who_granted, $course_id){

    }

    public static function is_authorized($user_id, $id, $type=IS_AUTH_TYPE_COURSE){

        $is_authorized = false;
        if($type == IS_AUTH_TYPE_UNIT){
            $unit = LMS_DBFunctions::retrieve_lesson($id);
            //LMS_Log::print_r($unit);
            //get module_id
            $user_course = LMS_DBFunctions::get_user_modules_by_user($user_id, $unit['module_id']);
            if(!empty($user_course))
                $is_authorized = true;
        }else if($type==IS_AUTH_TYPE_COURSE) {
            $course = LMS_DBFunctions::get_user_course_by_course($user_id, $id);
            //LMS_Log::print_r($course,__FUNCTION__);
            if ($course) {

                $is_authorized = true;
            }
        }
        return $is_authorized;
    }


    public static function enrolled_in_courses($student_id){
        $user_course = LMS_DBFunctions::get_user_courses($student_id);
        if(!empty($user_course)){
            return true;
        }
        return false;
    }


    public static function check_student_permissions(){
        if ( !is_user_logged_in() ) {
            $code = LMS_SCHOOL_NOT_LOGGED_IN;
        }elseif(!self::enrolled_in_courses(get_current_user_id())) {
            $code = LMS_SCHOOL_NOT_AUTHORIZED;
        }else{
            $code = LMS_SCHOOL_IS_AUTHORIZED;
        }
        return $code;
    }


    /**
     * Get All Students with arguments
     *
     * Parameters Information for Args Array:
     * orderby - Sort by 'ID', 'login', 'nicename', 'email', 'url', 'registered', 'display_name'.
     * order - ASC (ascending) or DESC (descending).
     * search - Use this argument to search users by email address, URL, ID, username or display_name.
     *
     * @param $args [ARRAY]
     * @return array
     */
    public static function get_all_students($args)
    {
        global $wpdb;
        $allStudents = array();
        $args["blog_id"] = "1";

        //Get All Users Registered in the System
        $registered_users = get_users( $args );
        foreach ($registered_users as $value) {
            $allStudents[$value->ID] = new LMS_StudentCourses($value->ID);
        }

        return $allStudents;
    }

}