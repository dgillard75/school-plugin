<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/1/15
 * Time: 3:22 PM
 */


include_once(LMS_PLUGIN_PATH.'inc/LMS_SchoolDB.php');
include_once(LMS_PLUGIN_PATH.'inc/AllStudentCourses.php');
include_once(LMS_PLUGIN_PATH.'inc/StudentCourse.php');


class StudentAccount {

    public $student;
    protected $allstudentcourses;


    public function StudentAccount($sid){
        $this->load($sid);
    }

    protected function load($sid){

        //get Student Information
        $this->student = LMS_SchoolDB::get_student($sid);
        //get Course Information

        $this->allstudentcourses = new AllStudentCourses($sid);


    }

    public function getInfo(){
        return $this->student;
    }

    public function getAllCourses(){
        return $this->allstudentcourses;
    }

    public function getCourse($course_id)
    {
        $individual_course = new StudentCourse("", "");
        //echo "<pre> Count:".$this->getNumberOfCourses()."</pre>";
        if($this->haveCourses()) {
            foreach ($this->allstudentcourses->getCourses() as $scourseObj) {
                $scourse = $scourseObj->getCourse();
                if ($scourse["course_id"] == $course_id) {
                    $individual_course = $scourse;
                    break;
                }
            }
        }
        return $individual_course;
    }

    public function haveCourses(){
        if($this->getNumberOfCourses()==0)
            return false;

        return true;
    }

    public function getNumberOfCourses(){
        return $this->allstudentcourses->getCount();
    }
}
?>