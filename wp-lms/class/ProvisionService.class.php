<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 7/15/15
 * Time: 11:03 AM
 */

include_once(LMS_PLUGIN_PATH."class/LMS_DBFunctions.class.php");

class ProvisionService {


    protected static function is_course_valid($course_id){
        $course = LMS_DBFunctions::retrieve_course($course_id);
        if(empty($course)){
            return false;
        }

        return true;
    }

    protected static function is_module_valid($course_id, $module_id)
    {
        $row = LMS_DBFunctions::get_course_module_by_id($module_id);
        LMS_Log::print_r($row);
        if(!empty($row) && $row['course_id']==$course_id){
            return true;
        }
        return false;
    }

    /**
     * @param $user_id
     * @param $who_granted
     * @param $course_id
     * @param string $access_type
     * @param null $module_id_list
     * @return bool|int
     */
    public static function grant_course_access($user_id, $who_granted, $course_id, $access_type="FULL", $module_id = NULL)
    {

        //validate course_id
        if (!self::is_course_valid($course_id)) {
            return false;
        }

        //validate
        if (!empty($module_id) && !self::is_module_valid($course_id, $module_id)) {
            return false;
        }

        $modules = LMS_DBFunctions::get_course_modules($course_id, true);


        foreach ($modules as $m) {
            $data[USER_MODULES_TBL_TRANS_ID] = $course_id;
            $data[USER_COURSES_TBL_USER_ID] = $user_id;
            $data[USER_COURSES_TBL_GRANTOR] = $who_granted;
            $data[USER_COURSES_TBL_STATUS] = 1;
            $data[USER_COURSES_TBL_MODULE_ID] = $m->module_id;
            LMS_Log::print_r($data);
        }



        //Grant Access - Update
        if ($access_type == "BY_MODULE") {
            $data[USER_COURSES_TBL_COURSE_ID] = $course_id;
            $data[USER_COURSES_TBL_USER_ID] = $user_id;
            $data[USER_COURSES_TBL_GRANTOR] = $who_granted;
            $data[USER_COURSES_TBL_STATUS] = 1;
            $data[USER_COURSES_TBL_MODULE_ID] = $module_id;
            LMS_Log::print_r($data);
        } else {
            //Add Course and AllModules
            $modules = LMS_DBFunctions::get_course_modules($course_id, true);
            //LMS_Log::print_r($modules);
            foreach ($modules as $m) {
                $data[USER_COURSES_TBL_COURSE_ID] = $course_id;
                $data[USER_COURSES_TBL_USER_ID] = $user_id;
                $data[USER_COURSES_TBL_GRANTOR] = $who_granted;
                $data[USER_COURSES_TBL_STATUS] = 1;
                $data[USER_COURSES_TBL_MODULE_ID] = $m->module_id;
                LMS_Log::print_r($data);
            }
        }//end of else
        return 0;
    }

    /**
     * @param $user_id
     * @param $who_granted
     * @param $course_id
     */
    public static function remove_course_access($user_id, $who_granted, $course_id){

    }
}