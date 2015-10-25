<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 7/10/15
 * Time: 12:54 PM
 */

include_once(LMS_PLUGIN_PATH . "class/LMS_DBFunctions.class.php");


class LMS_Repository
{

    protected $courses;
    protected $unit_count=0;

    public function LMS_Repository($cid = NULL)
    {
        $this->load_library($cid);
    }

    public function get_courses(){
        return $this->courses;
    }

    //private function load_course
    private function load_modules_and_lessons($modules, &$lesson_cnt)
    {
        $mArr = array();
        $lesson_cnt = 0;
        foreach ($modules as $m) {
            //$lessons = array();
            //LMS_Log::print_r($m);
            $mArr[$m->module_id] = (array)$m;
            $lessons = LMS_DBFunctions::retrieve_lesson_by_module($m->module_id);
            //LMS_Log::print_r($lessons);

            if (!empty($lessons)) {
                //$mArr[$m->module_id] = array();
                $mArr[$m->module_id]['lessons'] = $lessons;
                $lesson_cnt = $lesson_cnt + count($lessons);
                //LMS_Log::print_r($lessons);
                //exit();
            }
        }

        return $mArr;
    }


    protected function load_library($cid)
    {

        $courses_from_db = array();

        if (isset($cid)) {
            //only load a course
            $courses_from_db = LMS_DBFunctions::retrieve_course($cid);
            $modules = LMS_DBFunctions::get_course_modules($courses_from_db['id']);
            $mArr = $this->load_modules_and_lessons($modules,$lesson_count);
            $c = $courses_from_db;
            $c['modules'] = $mArr;
            $c['total_units'] = $lesson_count;
            $this->courses[] = $c;
            //LMS_Log::print_r($c);
            //LMS_Log::print_r($courses_from_db);
        } else {
            //load the entire library
            $courses_from_db = LMS_DBFunctions::get_active_courses(ARRAY_A);
            //LMS_Log::print_r($courses_from_db);
            foreach ($courses_from_db as $c) {
                //LMS_Log::print_r($c);
                $modules = LMS_DBFunctions::get_course_modules($c['id']);
                $mArr = $this->load_modules_and_lessons($modules,$lesson_count);
                $c['modules'] = $mArr;
                $c['total_units'] = $lesson_count;
                $this->courses[] = $c;
                //exit();
            }
        }
    }

    public function get_total_number_of_courses(){
        return count($this->courses);
    }

    public function get_total_number_of_units(){
        return $this->unit_count;
    }

}// end of Class
        /**
        foreach($courses_from_db as $c){
            LMS_Log::print_r($c);

            $modules = LMS_DBFunctions::get_course_modules($c['id']);
            $mArr = array();
            $courseArr = array();
            $courseArr = $c;
            LMS_Log::print_r($courseArr);
            exit();

            foreach($modules as $m){
                //$lessons = array();
                //LMS_Log::print_r($m);
                $mArr[$m->module_id] = $m;
                $lessons = LMS_DBFunctions::retrieve_lesson_by_module($m->module_id);
                //LMS_Log::print_r($lessons);

                if(!empty($lessons)){
                    $mArr[$m->module_id] = array();
                    $mArr[$m->module_id]['lessons'] = $lessons;
                    //LMS_Log::print_r($lessons);
                    //exit();
                }
            }
            //$courseArr[] =
            $c['modules'] = $mArr;
            $this->courses[] = $c;
        }
         * **/



?>