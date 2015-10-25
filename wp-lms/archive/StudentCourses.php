<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/1/15
 * Time: 5:19 PM
 */

include_once(LMS_INCLUDE_DIR.'LMS_SchoolDB.php');


class StudentCourse{

    protected $course; // Course Array containing price, name, product code
    protected $moduleArr; // List of Modules

    public function StudentCourse(){}

    public function StudentCourse($user_id, $course_id){
        $this->load($user_id, $course_id);
    }

    public function getCourse() { return $this->course; }

    public function getListOfModules() { return $this->moduleArr; }

    protected function load($uid,$cid)
    {
        //Get Course information
        $courseinfo = LMS_SchoolDB::retrieve_course($cid);
        $course["course_name"] = $courseinfo["cname"];
        $course["course_code"] = $courseinfo["ccode"];

        //get Course information pertaining to the specifice user.
        $usercourse = LMS_SchoolDB::get_student_course($uid, $cid);
        $this->course["start_date"] = $usercourse["start_date"];
        $this->course["end_date"] = $usercourse["end_date"];
        $this->course["signup_type"] = $usercourse["signup_type"];

        if ($usercourse["signup_type"] == "0") {
            $ucset = LMS_SchoolDB::get_user_courses_set($cid, $uid);
            $courseSet = LMS_SchoolDB::get_course_sets_by_setid($ucset["set_id"]);
            foreach ($courseSet as $set) {
                //echo "<pre> ID:" . $set->id . " ---- Set Name:" . $set->set_name . "</pre>\n";
                $mset = LMS_SchoolDB::get_set_modules($set->id);
                $module = LMS_SchoolDB::get_module($mset['module_id']);
                $mObj["set_id"] = $mset['set_id'];
                $mObj["set_name"] = $set->set_name;
                $mObj["module_id"] = $mset['module_id'];
                $mObj["module_name"] = $module["module_name"];
                $mObj["pageid"] = $module["pageid"];
                $mObj["tbl_order"] = $module["tbl_order"];
                $this->moduleArr[] = $mObj;
            }
        } else {
            $course_modules = LMS_SchoolDB::get_course_modules($cid);
            foreach ($course_modules as $cmodule) {
                $mObj["module_id"] = $cmodule['module_id'];
                $mObj["module_name"] = $cmodule["module_name"];
                $mObj["pageid"] = $cmodule["pageid"];
                $mObj["tbl_order"] = $cmodule["tbl_order"];
                $this->moduleArr[] = $mObj;
            }
        }

    }

    public function printObj(){
        print "<pre>";
        print_r($this->course);
        print "</pre>";

        print "<pre>";
        print_r($this->moduleArr);
        print "</pre>";
    }
}