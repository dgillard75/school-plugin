<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 7/16/15
 * Time: 12:47 PM
 */

//include_once(WPLMS_PLUGIN_PATH . 'class/LMS_DBFunctions.class.php');
//include_once(WPLMS_PLUGIN_PATH . 'class/LMS_StudentCourseList.class.php');

class LMS_StudentCourses {

    protected $userInfo;            // Student User Information
    protected $student_course_list; // Student Course List

    public function LMS_StudentCourses($uid){
        $this->userInfo = array();
        $this->student_course_list = array();
        $this->load($uid);
    }

    public function load($sid){
        //load User Information
        $this->userInfo = LMS_DBFunctions::get_student_wpuser_data($sid);

        //Now get UserCourse Information if available
        $usercourses = LMS_DBFunctions::get_user_courses($sid);
        for($i=0; $i < count($usercourses); $i++){
            $usercourseArr = get_object_vars($usercourses[$i]);
            $sc = new StudentCourseList();
            $sc->addCourse($usercourseArr);
            $this->student_course_list[] = $sc;
        }
    }

    public function getCourseList(){ return $this->student_course_list;}

    public function getUserInformation(){
        return $this->userInfo;
    }

    public function getCount(){
        return count($this->student_course_list);
    }

}