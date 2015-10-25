<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/1/15
 * Time: 5:19 PM
 */

include_once(LMS_PLUGIN_PATH.'inc/LMS_SchoolDB.php');
include_once(LMS_PLUGIN_PATH.'inc/StudentCourse.php');


class AllStudentCourses{

    public $studentcourses;

    public function AllStudentCourses($uid){
        $this->load($uid);
    }

    public function load($sid){
        $usercourses = LMS_SchoolDB::get_students_courses($sid);
        foreach($usercourses as $usercourse) {
            $courseArr = get_object_vars($usercourse);
            $this->studentcourses[] = new StudentCourse($sid,$courseArr['course_id']);
        }
    }

    public function getCourses(){ return $this->studentcourses;}

    public function getCount(){
        return count($this->studentcourses);
    }
}
?>