<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/1/15
 * Time: 5:19 PM
 */


include_once(LMS_PLUGIN_PATH.'class/LMS_DBFunctions.class.php');

define("STUDENT_COURSE_LIST_CID",  USER_COURSES_TBL_COURSE_ID);
define("STUDENT_COURSE_LIST_CNAME",COURSES_TBL_CNAME);
define("STUDENT_COURSE_LIST_TID", USER_COURSES_TBL_TRANS_ID);
define("STUDENT_COURSE_LIST_UID", USER_COURSES_SET_TBL_USER_ID);
define("STUDENT_COURSE_LIST_STATUS", USER_COURSES_TBL_STATUS);
define("STUDENT_COURSE_LIST_START_DATE", USER_COURSES_TBL_START_DATE);
define("STUDENT_COURSE_LIST_LESSON_CNT", "number_of_units");
define("STUDENT_COURSE_LIST_LESSONS", "lessons");
define("STUDENT_COURSE_LIST_QUIZ",    "quiz");



class StudentCourseList
{
    protected $course;    // Course Array containing price, name, product code
    protected $moduleArr; // List of Modules and Units

    public function StudentCourseList()
    {
        $this->init();
    }

    protected function init()
    {
        $this->course = array();
        $this->moduleArr = array();
    }

    public function isEmpty()
    {
        return empty($this->course);
    }

    public function getCourse()
    {
        return $this->course;
    }

    public function getListOfModules()
    {
        return $this->moduleArr;
    }

    public function addCourse($usercourseinfo)
    {
        $lesson_cnt = 0;
        $this->course[STUDENT_COURSE_LIST_UID] = $usercourseinfo[USER_COURSES_TBL_USER_ID];
        $this->course[STUDENT_COURSE_LIST_CID] = $usercourseinfo[USER_COURSES_TBL_COURSE_ID];
        $this->course[STUDENT_COURSE_LIST_TID] = $usercourseinfo[USER_COURSES_TBL_TRANS_ID];
        $this->course[STUDENT_COURSE_LIST_STATUS] = $usercourseinfo[USER_COURSES_TBL_STATUS];
        $this->course[STUDENT_COURSE_LIST_START_DATE] = $usercourseinfo[USER_COURSES_TBL_START_DATE];

        //Get Course Specific Information
        $coursedata = LMS_DBFunctions::retrieve_course($this->course[STUDENT_COURSE_LIST_CID]);

        $this->course[STUDENT_COURSE_LIST_CNAME] = $coursedata[COURSES_TBL_CNAME];

        //Get All UserModules for given Trans ID
        $usermodules = LMS_DBFunctions::get_user_modules($this->course[STUDENT_COURSE_LIST_TID]);

        //Get All Modules for Course sorted by Module Order
        $modules = LMS_DBFunctions::get_modules($this->course[STUDENT_COURSE_LIST_CID]);
        foreach ($modules as $m) {
            $tmp_module_array = get_object_vars($m);

            //Get User Status, Start Date information etc and store
            if (array_key_exists($m->module_id, $usermodules)) {
                $tmp_module_array['start_date'] = $usermodules[$m->module_id] -> start_date;
                $tmp_module_array['status'] = $usermodules[$m->module_id] -> status;
            }

	        $lessons = LMS_DBFunctions::retrieve_lesson_by_module($m->module_id);
            if (!empty($lessons)) {
                $tmp_module_array[STUDENT_COURSE_LIST_LESSONS] = $lessons;
                $lesson_cnt = $lesson_cnt + count($lessons);
            }

	        $q = LMS_DBFunctions::get_quiz($m->module_id);
	        if(!$q){
		        $tmp_module_array[STUDENT_COURSE_LIST_QUIZ] = array();
	        }else{
		        $tmp_module_array[ STUDENT_COURSE_LIST_QUIZ ] = $q;
	        }

            $this->moduleArr[] = $tmp_module_array;
        }
        $this->course[STUDENT_COURSE_LIST_LESSON_CNT] = $lesson_cnt;
        //For each Module set the correct Module Information
    }


    public function printObj()
    {

        print "<pre><h4>StudentCourseList::printObj</h4>";
        print_r($this->userInfo);
        print "</pre>";

        print "<pre>";
        print_r($this->course);
        print "</pre>";

        print "<pre>";
        print_r($this->moduleArr);
        print "</pre>";


    }
}
