<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/1/15
 * Time: 5:19 PM
 */

include_once(LMS_PLUGIN_PATH.'inc/LMS_SchoolDB.php');


class StudentCourse
{

    protected $course; // Course Array containing price, name, product code
    protected $moduleArr; // List of Modules

    public function StudentCourse($user_id, $course_id)
    {
        $this->init();
        if (!empty($user_id) && !empty($course_id))
            $this->load($user_id, $course_id);
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

    protected function load($uid, $cid)
    {
        //Get Course information
        $courseinfo = LMS_SchoolDB::retrieve_course($cid);
        $this->course["course_id"] = $cid;
        $this->course["course_name"] = $courseinfo["cname"];
        $this->course["course_code"] = $courseinfo["ccode"];
        $this->course["course_price"] = $courseinfo["cprice"];
        $this->course["course_time_type"] = $courseinfo["course_time_type"];
        $this->course["course_time"] = $courseinfo["ctime"];

        //get Course information pertaining to the specifice user.
        $usercourse = LMS_DBFunctions::get_user_courses($uid);
        for($i=0; $i < count($usercourse); $i++){

        }
        $usercourse = LMS_SchoolDB::get_student_course($uid, $cid);
        $this->course["id"] = $usercourse["id"];
        $this->course["start_date"] = $usercourse["start_date"];
        $this->course["end_date"] = $usercourse["end_date"];
        $this->course["signup_type"] = $usercourse["signup_type"];
        $this->course["status"] = $usercourse["status"];

        $modules = LMS_AdminFunctions::get_course_modules($cid);


        if ($usercourse["signup_type"] == "0") {
            $courseSet = LMS_SchoolDB::get_user_courses_set($cid, $uid);
            LMS_SchoolDB::debug(__CLASS__.":".__FUNCTION__.":".__LINE__."\t".print_r($courseSet, true));
            foreach ($courseSet as $set) {
                $x = LMS_SchoolDB::get_courses_set_by_cid_n_sid($set->set_id, $cid);
                LMS_SchoolDB::debug(__CLASS__.":".__FUNCTION__.":".__LINE__."\t".print_r($x, true));
                $mset = LMS_SchoolDB::get_set_modules($set->set_id);
                $module = LMS_SchoolDB::get_module($mset['module_id']);
                $mObj["paid_amount"] = $set->paid_amount;
                $mObj["set_id"] = $x['id'];
                $mObj["set_name"] = $x['set_name'];
                $mObj["module_id"] = $mset['module_id'];
                $mObj["module_name"] = $module["module_name"];
                $mObj["pageid"] = $module["pageid"];
                $mObj["module_number"] = $module["module_number"];
                $mObj["status"] = $set->status;
                $mObj["start_date"] = $set->start_date;
                $mObj["end_date"] = $set->end_date;

                $this->moduleArr[] = $mObj;
            }
        } else {
            $course_modules = LMS_SchoolDB::get_course_modules($cid);
            foreach ($course_modules as $cmodule) {
                $mObj["module_id"] = $cmodule->module_id;
                $mObj["module_name"] = $cmodule->module_name;
                $mObj["pageid"] = $cmodule->pageid;
                $mObj["module_number"] = $cmodule->module_number;
                $this->moduleArr[] = $mObj;
            }
        }
    }

    public function printObj()
    {
        print "<pre>";
        print_r($this->course);
        print "</pre>";

        print "<pre>";
        print_r($this->moduleArr);
        print "</pre>";
    }
}
?>