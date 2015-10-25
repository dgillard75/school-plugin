<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 10/23/15
 * Time: 5:00 PM
 */

class LMS_Student {

	protected $userInfo;            // Student User Information
	protected $courses;             // Student Course List

	public function LMS_StudentCourses($uid){
		$this->userInfo = array();
		$this->courses = array();
		$this->load($uid);
	}

	public function load($sid){
		//load User Information
		$this->userInfo = LMS_DBFunctions::get_student_wpuser_data($sid);


		//Now get UserCourse Information if available
		$usercourses = LMS_DBFunctions::get_user_courses($sid);
		for($i=0; $i < count($usercourses); $i++){
			$usercourseArr = get_object_vars($usercourses[$i]);
			$sc = new LMS_Courses();
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